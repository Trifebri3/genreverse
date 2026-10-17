<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Region;
use App\Services\CampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CampaignController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}

    /**
     * Display public list of advocacy campaigns.
     */
    public function index(Request $request): Response
    {
        $issueCategory = $request->input('category');
        $regionId = $request->input('region');

        $query = Campaign::with(['creator.profile', 'region', 'reports' => fn ($q) => $q->where('verification_status', 'verified')])
            ->where('status', 'published');

        if ($issueCategory) {
            $query->where('issue_category', $issueCategory);
        }

        if ($regionId) {
            $query->where('region_id', $regionId);
        }

        $campaigns = $query->latest('published_at')->paginate(9)->withQueryString();
        $regions = Region::where('level', 'province')->get();

        return Inertia::render('Advocacy/Index', [
            'campaigns' => $campaigns,
            'regions' => $regions,
            'selectedCategory' => $issueCategory,
            'selectedRegion' => $regionId,
        ]);
    }

    /**
     * Show detail of a single campaign.
     */
    public function show(Request $request, string $slug): Response
    {
        $campaign = Campaign::with([
            'creator.profile',
            'region',
            'reports' => fn ($q) => $q->where('verification_status', 'verified')->with('submitter.profile'),
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();
        $canManage = $user && ($user->id === $campaign->creator_id || $user->isAdmin());

        return Inertia::render('Advocacy/Show', [
            'campaign' => $campaign,
            'canManage' => $canManage,
        ]);
    }

    /**
     * Show campaign creation form.
     */
    public function create(Request $request): Response
    {
        $regions = Region::where('is_active', true)->get();

        return Inertia::render('Advocacy/Create', [
            'regions' => $regions,
        ]);
    }

    /**
     * Store newly created campaign draft.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'region_id' => ['required', 'exists:regions,id'],
            'issue_category' => ['required', 'string'],
            'problem_statement' => ['required', 'string'],
            'objective' => ['required', 'string'],
            'target_audience' => ['required', 'string'],
            'call_to_action' => ['required', 'string'],
            'planned_start' => ['nullable', 'date'],
            'planned_end' => ['nullable', 'date', 'after_or_equal:planned_start'],
        ]);

        $campaign = $this->campaignService->createCampaign($request->user(), $validated);

        return redirect()->route('campaigns.show', $campaign->slug)
            ->with('success', 'Rancangan kampanye berhasil dibuat! Silakan ajukan untuk peninjauan moderator.');
    }

    /**
     * Submit campaign for review.
     */
    public function submitReview(Request $request, Campaign $campaign): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->id === $campaign->creator_id || $user->isAdmin(), 403);

        $this->campaignService->submitForReview($campaign, $user);

        return back()->with('success', 'Kampanye berhasil diajukan untuk ditinjau oleh Admin/Moderator GenRe.');
    }

    /**
     * Submit activity report with verified evidence.
     */
    public function submitReport(Request $request, Campaign $campaign): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->id === $campaign->creator_id || $user->isAdmin(), 403);

        $validated = $request->validate([
            'activity_date' => ['required', 'date'],
            'summary' => ['required', 'string'],
            'claimed_participants' => ['required', 'integer', 'min:1'],
            'evidence_path' => ['nullable', 'string'],
        ]);

        $this->campaignService->submitReport($campaign, $user, $validated);

        return back()->with('success', 'Laporan kegiatan berhasil dikirim untuk verifikasi dampak!');
    }
}
