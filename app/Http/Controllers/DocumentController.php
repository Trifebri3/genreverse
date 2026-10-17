<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    /**
     * Display public documents and reports archive.
     */
    public function index(Request $request): Response
    {
        $query = Document::query()->where('is_public', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        $categories = Document::where('is_public', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        $totalDownloads = Document::where('is_public', true)->sum('downloads_count');

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
            'categories' => $categories,
            'totalDownloads' => $totalDownloads,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Download document file and increment download count.
     */
    public function download(Document $document)
    {
        $document->increment('downloads_count');

        if (Storage::disk('public')->exists($document->file_path)) {
            $fullPath = Storage::disk('public')->path($document->file_path);

            return response()->download($fullPath, $document->file_name);
        }

        // Fallback if file doesn't exist on disk, return with notice
        return redirect()->back()->with('error', 'Berkas dokumen belum tersedia di server.');
    }
}
