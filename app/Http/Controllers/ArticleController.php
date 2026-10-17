<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * Display public articles and news catalog.
     */
    public function index(Request $request): Response
    {
        $query = Article::query()->where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();

        $featured = Article::where('is_published', true)->latest('published_at')->first();

        $popularArticles = Article::where('is_published', true)
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        $categories = Article::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
            'featured' => $featured,
            'popularArticles' => $popularArticles,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Display single article detail.
     */
    public function show(string $slug): Response
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $article->increment('views_count');

        $relatedArticles = Article::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedArticles->isEmpty()) {
            $relatedArticles = Article::where('is_published', true)
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}
