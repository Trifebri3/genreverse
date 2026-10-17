<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): Response
    {
        $query = Article::query()->with('author.profile');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        $articles = $query->latest()->paginate(10)->withQueryString();

        $categories = Article::select('category')->distinct()->pluck('category');

        return Inertia::render('Admin/Articles/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:150',
            'cover_image' => 'nullable|image|max:5120', // max 5MB
            'cover_image_url' => 'nullable|url|max:1000',
            'is_published' => 'boolean',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('articles', 'public');
            $coverImagePath = Storage::url($path);
        } elseif (! empty($validated['cover_image_url'])) {
            $coverImagePath = $validated['cover_image_url'];
        }

        Article::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'cover_image' => $coverImagePath,
            'author_id' => $request->user()?->id,
            'author_name' => $validated['author_name'] ?? ($request->user()?->name ?? 'Admin GenRe'),
            'is_published' => $validated['is_published'] ?? true,
            'published_at' => ($validated['is_published'] ?? true) ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Berita berhasil dipublikasikan!');
    }

    /**
     * Update an article.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:150',
            'cover_image' => 'nullable|image|max:5120',
            'cover_image_url' => 'nullable|url|max:1000',
            'is_published' => 'boolean',
        ]);

        $coverImagePath = $article->cover_image;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('articles', 'public');
            $coverImagePath = Storage::url($path);
        } elseif (! empty($validated['cover_image_url'])) {
            $coverImagePath = $validated['cover_image_url'];
        }

        $article->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'author_name' => $validated['author_name'] ?? $article->author_name,
            'cover_image' => $coverImagePath,
            'is_published' => $validated['is_published'] ?? $article->is_published,
        ]);

        return redirect()->back()->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Article $article): RedirectResponse
    {
        $article->update([
            'is_published' => ! $article->is_published,
            'published_at' => ! $article->is_published ? now() : $article->published_at,
        ]);

        return redirect()->back()->with('success', 'Status publikasi berhasil diubah!');
    }

    /**
     * Delete an article.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->back()->with('success', 'Berita berhasil dihapus!');
    }
}
