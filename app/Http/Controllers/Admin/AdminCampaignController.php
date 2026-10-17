<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Services\CampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCampaignController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}

    /**
     * Display campaign submissions for admin/moderator review.
     */
    public function index(Request $request): Response
    {
        $status = $request->input('status');

        $query = Campaign::with(['creator.profile', 'region', 'reviewer.profile']);

        if ($status) {
            $query->where('status', $status);
        }

        $campaigns = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Campaigns', [
            'campaigns' => $campaigns,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Approve or reject a campaign submission.
     */
    public function review(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'approved' => ['required', 'boolean'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $this->campaignService->reviewCampaign(
            campaign: $campaign,
            reviewer: $request->user(),
            approved: $validated['approved'],
            reason: $validated['reason']
        );

        $msg = $validated['approved'] ? 'Kampanye berhasil disetujui dan dipublikasikan ke publik!' : 'Kampanye ditolak dengan catatan evaluasi.';

        return back()->with('success', $msg);
    }

    /**
     * Display campaign activity reports for impact verification.
     */
    public function reports(Request $request): Response
    {
        $reports = CampaignReport::with(['campaign.region', 'submitter.profile', 'reviewer.profile'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/CampaignReports', [
            'reports' => $reports,
        ]);
    }

    /**
     * Verify a campaign report and record confirmed participants.
     */
    public function verifyReport(Request $request, CampaignReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'verified' => ['required', 'boolean'],
            'verified_participants' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->campaignService->verifyReport(
            report: $report,
            reviewer: $request->user(),
            verified: $validated['verified'],
            verifiedParticipants: $validated['verified_participants'],
            notes: $validated['notes']
        );

        return back()->with('success', 'Laporan kegiatan berhasil diverifikasi dan statistik dampak diperbarui.');
    }
}
