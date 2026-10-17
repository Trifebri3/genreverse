<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoryController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Display published youth stories.
     */
    public function index(Request $request): Response
    {
        $stories = Story::where('status', 'published')
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Community/Stories', [
            'stories' => $stories,
        ]);
    }

    /**
     * Submit a story for moderation review.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:50'],
            'publication_mode' => ['required', 'in:public_name,pseudonym,anonymous'],
            'pen_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = $request->user();

        Story::create([
            'author_id' => $user?->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'publication_mode' => $validated['publication_mode'],
            'pen_name' => $validated['pen_name'] ?? null,
            'status' => 'under_review',
        ]);

        if ($user) {
            $this->gamificationService->awardXp(
                user: $user,
                points: 25,
                sourceType: 'story',
                sourceId: null,
                description: 'Mengirimkan cerita inspiratif GenRe'
            );
        }

        return back()->with('success', 'Ceritamu berhasil dikirim dan sedang dalam proses peninjauan moderator. Terima kasih telah berbagi inspirasi!');
    }
}
