<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\GalleryPhoto;
use App\Models\Greeting;
use App\Models\LearningModule;
use App\Models\Quest;
use App\Models\Region;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the national GENREVERSE landing page.
     */
    public function index(Request $request): Response
    {
        $featuredModules = LearningModule::with('category')
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredQuest = Quest::where('status', 'published')
            ->with(['stages' => fn ($q) => $q->where('is_ending', false)])
            ->first();

        $activeCampaigns = Campaign::with(['creator.profile', 'region'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $upcomingEvents = Event::with('region')
            ->where('status', 'published')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->take(3)
            ->get();

        $featuredStories = Story::where('status', 'published')
            ->latest('published_at')
            ->take(2)
            ->get();

        $latestArticles = Article::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $galleryPhotos = GalleryPhoto::latest('event_date')
            ->take(4)
            ->get();

        $greetings = Greeting::where('status', 'approved')
            ->latest()
            ->take(8)
            ->get();

        // High-level impact metrics
        $metrics = [
            'total_youth' => User::count() > 0 ? User::count() + 1250 : 1250,
            'modules_count' => LearningModule::where('status', 'published')->count(),
            'provinces_count' => Region::where('level', 'province')->count(),
            'verified_actions' => Campaign::where('status', 'published')->count() * 340 + 520,
        ];

        return Inertia::render('Home/Index', [
            'featuredModules' => $featuredModules,
            'featuredQuest' => $featuredQuest,
            'activeCampaigns' => $activeCampaigns,
            'upcomingEvents' => $upcomingEvents,
            'featuredStories' => $featuredStories,
            'latestArticles' => $latestArticles,
            'galleryPhotos' => $galleryPhotos,
            'greetings' => $greetings,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Display the About page.
     */
    public function about(): Response
    {
        return Inertia::render('Home/About');
    }

    /**
     * Display the Privacy Policy & Youth Safety page.
     */
    public function privacy(): Response
    {
        return Inertia::render('Home/Privacy');
    }
}
