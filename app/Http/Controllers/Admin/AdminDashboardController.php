<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Document;
use App\Models\GalleryPhoto;
use App\Models\Greeting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin publication management overview.
     */
    public function index(Request $request): Response
    {
        $stats = [
            'total_articles' => Article::count(),
            'total_documents' => Document::count(),
            'total_photos' => GalleryPhoto::count(),
            'total_greetings' => Greeting::count(),
            'pending_greetings' => Greeting::where('status', 'pending')->count(),
        ];

        $recentArticles = Article::latest()
            ->take(5)
            ->get();

        $recentGreetings = Greeting::latest()
            ->take(5)
            ->get();

        $recentDocuments = Document::latest()
            ->take(4)
            ->get();

        $recentPhotos = GalleryPhoto::latest()
            ->take(4)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentArticles' => $recentArticles,
            'recentGreetings' => $recentGreetings,
            'recentDocuments' => $recentDocuments,
            'recentPhotos' => $recentPhotos,
        ]);
    }
}
