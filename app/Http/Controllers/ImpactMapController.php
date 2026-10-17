<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Models\Event;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ImpactMapController extends Controller
{
    /**
     * Display the national Impact Map with province aggregated verification data.
     */
    public function index(Request $request): Response
    {
        $selectedProvinceCode = $request->input('province', 'ALL');

        // Fetch regions with active campaigns and verified reports
        $provinces = Region::where('level', 'province')
            ->withCount([
                'campaigns' => fn ($q) => $q->where('status', 'published'),
                'events' => fn ($q) => $q->where('status', 'published'),
            ])
            ->get();

        $query = CampaignReport::with(['campaign.region', 'campaign.creator.profile'])
            ->where('verification_status', 'verified');

        if ($selectedProvinceCode !== 'ALL') {
            $query->whereHas('campaign.region', fn ($q) => $q->where('code', $selectedProvinceCode));
        }

        $verifiedReports = $query->latest('activity_date')->take(20)->get();

        // Calculate verified totals
        $totalVerifiedParticipants = CampaignReport::where('verification_status', 'verified')->sum('verified_participants');
        $totalPublishedCampaigns = Campaign::where('status', 'published')->count();
        $totalPublishedEvents = Event::where('status', 'published')->count();

        return Inertia::render('Community/ImpactMap', [
            'provinces' => $provinces,
            'selectedProvince' => $selectedProvinceCode,
            'verifiedReports' => $verifiedReports,
            'stats' => [
                'total_verified_participants' => (int) $totalVerifiedParticipants,
                'total_published_campaigns' => (int) $totalPublishedCampaigns,
                'total_published_events' => (int) $totalPublishedEvents,
                'total_provinces' => $provinces->count(),
            ],
        ]);
    }
}
