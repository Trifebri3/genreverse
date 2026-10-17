<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Models\User;
use Illuminate\Support\Str;

class CampaignService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Create a new campaign draft.
     */
    public function createCampaign(User $user, array $data): Campaign
    {
        $data['creator_id'] = $user->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        $data['status'] = 'draft';

        $campaign = Campaign::create($data);

        AuditLog::log(
            actorId: $user->id,
            action: 'campaign.created',
            subjectType: Campaign::class,
            subjectId: $campaign->id,
            metadata: ['title' => $campaign->title]
        );

        return $campaign;
    }

    /**
     * Submit campaign for review.
     */
    public function submitForReview(Campaign $campaign, User $user): Campaign
    {
        $campaign->update([
            'status' => 'in_review',
        ]);

        AuditLog::log(
            actorId: $user->id,
            action: 'campaign.submitted_review',
            subjectType: Campaign::class,
            subjectId: $campaign->id
        );

        return $campaign;
    }

    /**
     * Review campaign (approve/publish or reject).
     */
    public function reviewCampaign(Campaign $campaign, User $reviewer, bool $approved, ?string $reason = null): Campaign
    {
        $status = $approved ? 'published' : 'rejected';
        $publishedAt = $approved ? now() : null;

        $campaign->update([
            'status' => $status,
            'reviewed_by' => $reviewer->id,
            'rejection_reason' => $approved ? null : $reason,
            'published_at' => $publishedAt,
        ]);

        AuditLog::log(
            actorId: $reviewer->id,
            action: $approved ? 'campaign.approved' : 'campaign.rejected',
            subjectType: Campaign::class,
            subjectId: $campaign->id,
            metadata: ['reason' => $reason]
        );

        if ($approved) {
            // Award creator 50 XP for getting their advocacy campaign approved
            $this->gamificationService->awardXp(
                user: $campaign->creator,
                points: 50,
                sourceType: 'campaign',
                sourceId: $campaign->id,
                description: "Kampanye disetujui: {$campaign->title}"
            );
        }

        return $campaign;
    }

    /**
     * Submit an activity report for a campaign.
     */
    public function submitReport(Campaign $campaign, User $submitter, array $data): CampaignReport
    {
        $data['campaign_id'] = $campaign->id;
        $data['submitted_by'] = $submitter->id;
        $data['verification_status'] = 'pending';

        $report = CampaignReport::create($data);

        AuditLog::log(
            actorId: $submitter->id,
            action: 'campaign_report.submitted',
            subjectType: CampaignReport::class,
            subjectId: $report->id
        );

        return $report;
    }

    /**
     * Verify campaign report.
     */
    public function verifyReport(CampaignReport $report, User $reviewer, bool $verified, int $verifiedParticipants, ?string $notes = null): CampaignReport
    {
        $status = $verified ? 'verified' : 'rejected';

        $report->update([
            'verification_status' => $status,
            'verified_participants' => $verified ? $verifiedParticipants : 0,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);

        AuditLog::log(
            actorId: $reviewer->id,
            action: $verified ? 'campaign_report.verified' : 'campaign_report.rejected',
            subjectType: CampaignReport::class,
            subjectId: $report->id,
            metadata: ['verified_participants' => $verifiedParticipants]
        );

        return $report;
    }
}
