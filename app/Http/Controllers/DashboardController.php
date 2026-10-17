<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\EventRegistration;
use App\Models\QuestAttempt;
use App\Models\UserLessonProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display personal user dashboard.
     */
    public function index(Request $request): Response
    {
        $user = $request->user()->load(['roles', 'profile', 'ambassador.region', 'badges']);

        $totalXp = $user->totalXp();
        $level = $user->level();
        $nextLevelThreshold = match ($level) {
            1 => 100,
            2 => 300,
            3 => 600,
            4 => 1000,
            default => 1000 + ($level - 4) * 500,
        };

        // Recent XP activity
        $recentXp = $user->xpTransactions()->latest()->take(6)->get();

        // All badges with earned status
        $earnedBadgeIds = $user->badges->pluck('id')->toArray();
        $allBadges = Badge::where('is_active', true)->get()->map(function ($b) use ($earnedBadgeIds, $user) {
            $isEarned = in_array($b->id, $earnedBadgeIds);
            $pivot = $isEarned ? $user->badges->firstWhere('id', $b->id)?->pivot : null;

            return [
                'id' => $b->id,
                'code' => $b->code,
                'name' => $b->name,
                'description' => $b->description,
                'icon_path' => $b->icon_path,
                'is_earned' => $isEarned,
                'awarded_at' => $pivot?->awarded_at,
            ];
        });

        // Modules in progress
        $completedLessonCount = $user->lessonProgress()->where('status', 'completed')->count();
        $recentLessonProgress = UserLessonProgress::with('lesson.module.category')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->take(4)
            ->get();

        // Quest completions
        $questAttempts = QuestAttempt::with('quest')
            ->where('user_id', $user->id)
            ->latest('started_at')
            ->take(3)
            ->get();

        // Registered events
        $upcomingRegisteredEvents = EventRegistration::with('event.region')
            ->where('user_id', $user->id)
            ->whereHas('event', fn ($q) => $q->where('starts_at', '>=', now()))
            ->latest()
            ->take(3)
            ->get();

        // User campaigns if ambassador
        $userCampaigns = $user->isAmbassador() ? $user->campaigns()->with('reports')->latest()->take(4)->get() : [];

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_xp' => $totalXp,
                'level' => $level,
                'next_level_threshold' => $nextLevelThreshold,
                'completed_lessons' => $completedLessonCount,
                'badges_count' => count($earnedBadgeIds),
            ],
            'recentXp' => $recentXp,
            'badges' => $allBadges,
            'recentLessonProgress' => $recentLessonProgress,
            'questAttempts' => $questAttempts,
            'upcomingEvents' => $upcomingRegisteredEvents,
            'userCampaigns' => $userCampaigns,
        ]);
    }
}
