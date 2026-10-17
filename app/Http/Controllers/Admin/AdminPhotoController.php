<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminPhotoController extends Controller
{
    /**
     * Display a listing of gallery photos.
     */
    public function index(Request $request): Response
    {
        $query = GalleryPhoto::query()->with('uploader.profile');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%")
                    ->orWhere('album_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('album') && $request->input('album') !== 'all') {
            $query->where('album_name', $request->input('album'));
        }

        $photos = $query->latest()->paginate(12)->withQueryString();

        $albums = GalleryPhoto::select('album_name')->distinct()->pluck('album_name');

        return Inertia::render('Admin/Photos/Index', [
            'photos' => $photos,
            'albums' => $albums,
            'filters' => $request->only(['search', 'album']),
        ]);
    }

    /**
     * Store a newly created photo.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'album_name' => 'required|string|max:100',
            'caption' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:10240', // max 10MB
            'photo_url' => 'nullable|url|max:1000',
            'event_date' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('gallery', 'public');
            $imagePath = Storage::url($path);
        } elseif ($request->filled('photo_url')) {
            $imagePath = $request->input('photo_url');
        } else {
            return redirect()->back()->withErrors(['photo' => 'Silakan pilih file foto atau masukkan tautan gambar.']);
        }

        GalleryPhoto::create([
            'title' => $request->input('title'),
            'album_name' => $request->input('album_name'),
            'caption' => $request->input('caption'),
            'image_path' => $imagePath,
            'event_date' => $request->input('event_date') ?? now()->toDateString(),
            'is_featured' => $request->boolean('is_featured', false),
            'uploaded_by' => $request->user()?->id,
        ]);

        return redirect()->back()->with('success', 'Foto dokumentasi kegiatan berhasil diunggah!');
    }

    /**
     * Update photo information.
     */
    public function update(Request $request, GalleryPhoto $photo): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'album_name' => 'required|string|max:100',
            'caption' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:10240',
            'photo_url' => 'nullable|url|max:1000',
            'event_date' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        $imagePath = $photo->image_path;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('gallery', 'public');
            $imagePath = Storage::url($path);
        } elseif ($request->filled('photo_url')) {
            $imagePath = $request->input('photo_url');
        }

        $photo->update([
            'title' => $request->input('title'),
            'album_name' => $request->input('album_name'),
            'caption' => $request->input('caption'),
            'image_path' => $imagePath,
            'event_date' => $request->input('event_date') ?? $photo->event_date,
            'is_featured' => $request->boolean('is_featured', $photo->is_featured),
        ]);

        return redirect()->back()->with('success', 'Informasi foto berhasil diperbarui!');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(GalleryPhoto $photo): RedirectResponse
    {
        $photo->update(['is_featured' => ! $photo->is_featured]);

        return redirect()->back()->with('success', 'Status sorotan foto berhasil diperbarui!');
    }

    /**
     * Delete photo.
     */
    public function destroy(GalleryPhoto $photo): RedirectResponse
    {
        $photo->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus dari galeri!');
    }
}
