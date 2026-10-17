<?php

namespace App\Services;

use App\Models\Quest;
use App\Models\QuestAttempt;
use App\Models\QuestChoice;
use App\Models\QuestResponse;
use App\Models\QuestStage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuestService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Start a new quest attempt.
     */
    public function startAttempt(User $user, Quest $quest): QuestAttempt
    {
        $firstStage = $quest->stages()->where('is_ending', false)->orderBy('sort_order')->firstOrFail();

        return QuestAttempt::create([
            'user_id' => $user->id,
            'quest_id' => $quest->id,
            'current_stage_id' => $firstStage->id,
            'quest_version' => $quest->version,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Record a player choice, advance stage, and complete quest when ending is reached.
     */
    public function recordChoice(QuestAttempt $attempt, int $stageId, int $choiceId): array
    {
        return DB::transaction(function () use ($attempt, $stageId, $choiceId) {
            $stage = QuestStage::where('quest_id', $attempt->quest_id)->findOrFail($stageId);
            $choice = QuestChoice::where('stage_id', $stage->id)->findOrFail($choiceId);

            // Record choice response
            QuestResponse::create([
                'attempt_id' => $attempt->id,
                'stage_id' => $stage->id,
                'choice_id' => $choice->id,
                'created_at' => now(),
            ]);

            $nextStage = $choice->nextStage;
            $isEnding = $nextStage ? $nextStage->is_ending : true;

            $attempt->update([
                'current_stage_id' => $nextStage?->id,
                'status' => $isEnding ? 'completed' : 'in_progress',
                'completed_at' => $isEnding ? now() : null,
            ]);

            if ($isEnding) {
                // Award XP once for completion
                $quest = $attempt->quest;
                $this->gamificationService->awardXp(
                    user: $attempt->user,
                    points: $quest->xp_reward,
                    sourceType: 'quest',
                    sourceId: $quest->id,
                    description: "Menyelesaikan LifeQuest: {$quest->title}"
                );
            }

            return [
                'choice_feedback' => $choice->feedback,
                'impact_summary' => $choice->impact_summary,
                'next_stage' => $nextStage,
                'is_completed' => $isEnding,
            ];
        });
    }
}
