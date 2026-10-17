<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminDocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request): Response
    {
        $query = Document::query()->with('uploader.profile');

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

        $categories = Document::select('category')->distinct()->pluck('category');

        return Inertia::render('Admin/Documents/Index', [
            'documents' => $documents,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', // max 25MB
            'is_public' => 'boolean',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileType = strtolower($file->getClientOriginalExtension());
        $fileSize = $file->getSize();
        $storedPath = $file->store('documents', 'public');

        Document::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')).'-'.Str::random(5),
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'file_path' => $storedPath,
            'file_name' => $originalName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'is_public' => $request->boolean('is_public', true),
            'uploaded_by' => $request->user()?->id,
        ]);

        return redirect()->back()->with('success', 'Dokumen/Laporan berhasil diunggah!');
    }

    /**
     * Update an existing document.
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            'is_public' => 'boolean',
        ]);

        $updateData = [
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'is_public' => $request->boolean('is_public', $document->is_public),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Remove previous file if exists on public disk
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $updateData['file_path'] = $file->store('documents', 'public');
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_type'] = strtolower($file->getClientOriginalExtension());
            $updateData['file_size'] = $file->getSize();
        }

        $document->update($updateData);

        return redirect()->back()->with('success', 'Informasi dokumen berhasil diperbarui!');
    }

    /**
     * Delete document.
     */
    public function destroy(Document $document): RedirectResponse
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }
}
