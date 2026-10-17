<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\ContentReport;
use App\Models\ModerationAction;
use App\Models\Question;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ModerationService
{
    /**
     * Submit a user report on content.
     */
    public function reportContent(?User $reporter, string $contentType, int $contentId, string $reason, ?string $description = null): ContentReport
    {
        return ContentReport::create([
            'reporter_id' => $reporter?->id,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    /**
     * Resolve a content report by a moderator.
     */
    public function resolveReport(ContentReport $report, User $moderator, string $action, string $reason): ModerationAction
    {
        return DB::transaction(function () use ($report, $moderator, $action, $reason) {
            $report->update([
                'status' => $action === 'dismiss' ? 'dismissed' : 'action_taken',
            ]);

            $modAction = ModerationAction::create([
                'report_id' => $report->id,
                'moderator_id' => $moderator->id,
                'action' => $action,
                'reason' => $reason,
                'created_at' => now(),
            ]);

            AuditLog::log(
                actorId: $moderator->id,
                action: 'moderation.report_resolved',
                subjectType: ContentReport::class,
                subjectId: $report->id,
                metadata: ['action' => $action, 'reason' => $reason]
            );

            return $modAction;
        });
    }

    /**
     * Approve or reject a submitted Story.
     */
    public function moderateStory(Story $story, User $moderator, bool $approved): Story
    {
        $story->update([
            'status' => $approved ? 'published' : 'rejected',
            'reviewed_by' => $moderator->id,
            'published_at' => $approved ? now() : null,
        ]);

        AuditLog::log(
            actorId: $moderator->id,
            action: $approved ? 'story.approved' : 'story.rejected',
            subjectType: Story::class,
            subjectId: $story->id
        );

        return $story;
    }

    /**
     * Approve or reject a submitted Question.
     */
    public function moderateQuestion(Question $question, User $moderator, bool $approved): Question
    {
        $question->update([
            'status' => $approved ? 'approved' : 'rejected',
            'reviewed_by' => $moderator->id,
        ]);

        AuditLog::log(
            actorId: $moderator->id,
            action: $approved ? 'question.approved' : 'question.rejected',
            subjectType: Question::class,
            subjectId: $question->id
        );

        return $question;
    }
}
