<?php

namespace App\Services;

use App\Models\LearningModule;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserLessonProgress;
use Illuminate\Support\Facades\DB;

class LearningService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Mark a lesson as completed and award XP.
     */
    public function completeLesson(User $user, Lesson $lesson): UserLessonProgress
    {
        return DB::transaction(function () use ($user, $lesson) {
            $progress = UserLessonProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'status' => 'completed',
                    'progress_percent' => 100,
                    'completed_at' => now(),
                ]
            );

            // Award 20 XP for completing lesson
            $this->gamificationService->awardXp(
                user: $user,
                points: 20,
                sourceType: 'lesson',
                sourceId: $lesson->id,
                description: "Menyelesaikan materi: {$lesson->title}"
            );

            return $progress;
        });
    }

    /**
     * Calculate user's overall progress on a module.
     */
    public function getModuleProgress(User $user, LearningModule $module): array
    {
        $totalLessons = $module->lessons()->count();
        if ($totalLessons === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percent' => 0,
                'is_completed' => false,
            ];
        }

        $lessonIds = $module->lessons()->pluck('id');
        $completedLessons = UserLessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();

        $percent = (int) round(($completedLessons / $totalLessons) * 100);

        return [
            'total' => $totalLessons,
            'completed' => $completedLessons,
            'percent' => $percent,
            'is_completed' => $percent === 100,
        ];
    }
}
