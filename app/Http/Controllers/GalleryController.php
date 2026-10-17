<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    /**
     * Display public gallery photos and albums.
     */
    public function index(Request $request): Response
    {
        $query = GalleryPhoto::query();

        if ($request->filled('album') && $request->input('album') !== 'all') {
            $query->where('album_name', $request->input('album'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%");
            });
        }

        $photos = $query->latest('event_date')->paginate(12)->withQueryString();

        $albums = GalleryPhoto::select('album_name')->distinct()->pluck('album_name');

        $featuredPhotos = GalleryPhoto::where('is_featured', true)->latest()->take(4)->get();

        return Inertia::render('Gallery/Index', [
            'photos' => $photos,
            'albums' => $albums,
            'featuredPhotos' => $featuredPhotos,
            'filters' => $request->only(['album', 'search']),
        ]);
    }
}
