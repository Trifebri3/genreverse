<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AskDutaController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Display Tanya Duta QA forum with published questions & answers.
     */
    public function index(Request $request): Response
    {
        $category = $request->input('category');

        $query = Question::with(['answers.author.profile'])
            ->where('status', 'answered');

        if ($category) {
            $query->where('category', $category);
        }

        $questions = $query->latest()->paginate(8)->withQueryString();

        return Inertia::render('Community/AskDuta', [
            'questions' => $questions,
            'selectedCategory' => $category,
        ]);
    }

    /**
     * Submit an anonymous or identified question for consultation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string'],
            'question_body' => ['required', 'string', 'min:20', 'max:1000'],
            'is_anonymous' => ['required', 'boolean'],
        ]);

        $user = $request->user();

        $ref = 'QST-'.date('Y').'-'.strtoupper(Str::random(4));

        Question::create([
            'user_id' => $user?->id,
            'public_reference' => $ref,
            'category' => $validated['category'],
            'question_body' => $validated['question_body'],
            'is_anonymous' => $validated['is_anonymous'],
            'status' => 'pending_review',
        ]);

        return back()->with('success', "Pertanyaanmu dengan nomor rujukan [{$ref}] telah diterima secara aman dan akan dijawab oleh Duta GenRe setelah moderasi.");
    }

    /**
     * Answer a question (Ambassadors / Facilitators / Admins only).
     */
    public function answer(Request $request, Question $question): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->isAmbassador() || $user->isAdmin(), 403);

        $validated = $request->validate([
            'answer_body' => ['required', 'string', 'min:30'],
            'referral_resources' => ['nullable', 'string'],
        ]);

        Answer::create([
            'question_id' => $question->id,
            'author_id' => $user->id,
            'answer_body' => $validated['answer_body'],
            'referral_resources' => $validated['referral_resources'],
            'status' => 'published',
            'published_at' => now(),
        ]);

        $question->update([
            'status' => 'answered',
            'assigned_to' => $user->id,
        ]);

        $this->gamificationService->awardXp(
            user: $user,
            points: 30,
            sourceType: 'answer',
            sourceId: $question->id,
            description: "Menjawab Tanya Duta [{$question->public_reference}]"
        );

        return back()->with('success', 'Jawaban edukatif berhasil diterbitkan untuk publik!');
    }
}
