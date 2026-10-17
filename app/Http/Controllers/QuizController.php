<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    /**
     * Show quiz player interface.
     */
    public function show(Request $request, Quiz $quiz): Response
    {
        $user = $request->user();

        // Load quiz questions and options without exposing is_correct
        $quizData = Quiz::with([
            'module',
            'questions' => function ($q) {
                $q->orderBy('sort_order')->with(['options' => function ($opt) {
                    $opt->select('id', 'question_id', 'option_text', 'sort_order')->orderBy('sort_order');
                }]);
            },
        ])->findOrFail($quiz->id);

        // Check if there is an in-progress attempt or user previous attempts
        $previousAttempts = $user ? $quiz->attempts()->where('user_id', $user->id)->latest()->take(5)->get() : [];

        return Inertia::render('Academy/QuizPlayer', [
            'quiz' => $quizData,
            'previousAttempts' => $previousAttempts,
        ]);
    }

    /**
     * Start a new attempt.
     */
    public function startAttempt(Request $request, Quiz $quiz): JsonResponse
    {
        $user = $request->user();
        $attempt = $this->quizService->startAttempt($user, $quiz);

        return response()->json([
            'attempt_id' => $attempt->id,
            'status' => 'started',
        ]);
    }

    /**
     * Submit answers for an attempt.
     */
    public function submitAttempt(Request $request, QuizAttempt $attempt): JsonResponse
    {
        $user = $request->user();
        abort_unless($attempt->user_id === $user->id, 403);

        $validated = $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $result = $this->quizService->submitAnswers($attempt, $validated['answers']);

        return response()->json($result);
    }
}
