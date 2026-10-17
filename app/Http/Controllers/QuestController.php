<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\QuestAttempt;
use App\Services\QuestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestController extends Controller
{
    public function __construct(
        protected QuestService $questService
    ) {}

    /**
     * Display the LifeQuest directory.
     */
    public function index(Request $request): Response
    {
        $quests = Quest::withCount('stages')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(6);

        $user = $request->user();
        $userAttempts = $user ? QuestAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('quest_id')
            ->toArray() : [];

        return Inertia::render('LifeQuest/Index', [
            'quests' => $quests,
            'completedQuestIds' => $userAttempts,
        ]);
    }

    /**
     * Show detail synopsis of a quest.
     */
    public function show(Request $request, Quest $quest): Response
    {
        $user = $request->user();
        $attempt = $user ? $quest->attempts()->where('user_id', $user->id)->latest()->first() : null;

        return Inertia::render('LifeQuest/Show', [
            'quest' => $quest->load('stages'),
            'lastAttempt' => $attempt,
        ]);
    }

    /**
     * Play interactive branching LifeQuest.
     */
    public function play(Request $request, Quest $quest): Response
    {
        $user = $request->user();
        if (! $user) {
            return Inertia::render('LifeQuest/PlayerGuest', [
                'quest' => $quest->load(['stages.choices' => fn ($q) => $q->orderBy('sort_order')]),
            ]);
        }

        // Check if user has an existing in-progress attempt, otherwise create one
        $attempt = QuestAttempt::with(['currentStage.choices'])
            ->where('user_id', $user->id)
            ->where('quest_id', $quest->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if (! $attempt) {
            $attempt = $this->questService->startAttempt($user, $quest);
            $attempt->load(['currentStage.choices']);
        }

        return Inertia::render('LifeQuest/Player', [
            'quest' => $quest,
            'attempt' => $attempt,
            'currentStage' => $attempt->currentStage,
        ]);
    }

    /**
     * Start an attempt via API/fetch.
     */
    public function startAttempt(Request $request, Quest $quest): JsonResponse
    {
        $user = $request->user();
        $attempt = $this->questService->startAttempt($user, $quest);
        $attempt->load(['currentStage.choices']);

        return response()->json([
            'attempt' => $attempt,
            'currentStage' => $attempt->currentStage,
        ]);
    }

    /**
     * Submit a choice in the current stage.
     */
    public function choose(Request $request, QuestAttempt $attempt): JsonResponse
    {
        $user = $request->user();
        abort_unless($attempt->user_id === $user->id, 403);

        $validated = $request->validate([
            'stage_id' => ['required', 'integer'],
            'choice_id' => ['required', 'integer'],
        ]);

        $result = $this->questService->recordChoice(
            attempt: $attempt,
            stageId: $validated['stage_id'],
            choiceId: $validated['choice_id']
        );

        // Load fresh next stage with choices if not ended
        $nextStage = $result['next_stage'];
        if ($nextStage) {
            $nextStage->load('choices');
        }

        return response()->json([
            'choice_feedback' => $result['choice_feedback'],
            'impact_summary' => $result['impact_summary'],
            'next_stage' => $nextStage,
            'is_completed' => $result['is_completed'],
            'xp_reward' => $result['is_completed'] ? $attempt->quest->xp_reward : 0,
        ]);
    }
}
