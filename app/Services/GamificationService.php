<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Award XP to user with strict idempotency to prevent double-claiming.
     */
    public function awardXp(User $user, int $points, string $sourceType, ?int $sourceId = null, ?string $description = null): ?XpTransaction
    {
        $idempotencyKey = "xp_{$user->id}_{$sourceType}_".($sourceId ?? 'general').'_'.date('Ymd');
        if ($sourceType === 'quest' || $sourceType === 'lesson' || $sourceType === 'quiz') {
            // Permanent uniqueness for specific educational activities
            $idempotencyKey = "xp_{$user->id}_{$sourceType}_".($sourceId ?? 'activity');
        }

        return DB::transaction(function () use ($user, $points, $sourceType, $sourceId, $description, $idempotencyKey) {
            $existing = XpTransaction::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                return $existing;
            }

            $tx = XpTransaction::create([
                'user_id' => $user->id,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'idempotency_key' => $idempotencyKey,
                'points' => $points,
                'description' => $description ?? "Menyelesaikan kegiatan {$sourceType}",
                'created_at' => now(),
            ]);

            // Check if this XP triggers any badges
            $this->checkAndAwardBadges($user);

            return $tx;
        });
    }

    /**
     * Check badge criteria and award unearned badges.
     */
    public function checkAndAwardBadges(User $user): array
    {
        $awarded = [];
        $earnedBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        // 1. First Step: completed at least 1 lesson
        if (! in_array(1, $earnedBadgeIds)) {
            $completedLessons = $user->lessonProgress()->where('status', 'completed')->count();
            if ($completedLessons >= 1) {
                $badge = Badge::where('code', 'first_step')->first();
                if ($badge && ! $user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['awarded_at' => now()]);
                    $awarded[] = $badge;
                }
            }
        }

        // 2. Quiz Master: score >= 85
        $quizMasterBadge = Badge::where('code', 'quiz_master')->first();
        if ($quizMasterBadge && ! $user->badges()->where('badge_id', $quizMasterBadge->id)->exists()) {
            $hasHighQuizScore = $user->quizAttempts()->where('score', '>=', 85)->exists();
            if ($hasHighQuizScore) {
                $user->badges()->attach($quizMasterBadge->id, ['awarded_at' => now()]);
                $awarded[] = $quizMasterBadge;
            }
        }

        // 3. Quest Explorer: completed at least 1 quest
        $questBadge = Badge::where('code', 'quest_explorer')->first();
        if ($questBadge && ! $user->badges()->where('badge_id', $questBadge->id)->exists()) {
            $hasCompletedQuest = $user->questAttempts()->where('status', 'completed')->exists();
            if ($hasCompletedQuest) {
                $user->badges()->attach($questBadge->id, ['awarded_at' => now()]);
                $awarded[] = $questBadge;
            }
        }

        return $awarded;
    }
}
