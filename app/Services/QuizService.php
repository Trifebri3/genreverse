<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Start a new quiz attempt for user.
     */
    public function startAttempt(User $user, Quiz $quiz): QuizAttempt
    {
        return QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'score' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Submit answers, grade attempt, and award XP if passed.
     *
     * @param  array<int, int|string>  $answers  Map of question_id => selected_option_id
     */
    public function submitAnswers(QuizAttempt $attempt, array $answers): array
    {
        return DB::transaction(function () use ($attempt, $answers) {
            $quiz = $attempt->quiz()->with('questions.options')->firstOrFail();
            $questions = $quiz->questions;
            $totalQuestions = $questions->count();
            $correctCount = 0;
            $details = [];

            foreach ($questions as $question) {
                $selectedOptionId = $answers[$question->id] ?? null;
                $correctOption = $question->options->firstWhere('is_correct', true);
                $isCorrect = false;

                if ($selectedOptionId && $correctOption && (int) $selectedOptionId === (int) $correctOption->id) {
                    $isCorrect = true;
                    $correctCount++;
                }

                QuizAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $selectedOptionId ? (int) $selectedOptionId : null,
                    'is_correct' => $isCorrect,
                ]);

                $details[] = [
                    'question_id' => $question->id,
                    'question_text' => $question->question_text,
                    'is_correct' => $isCorrect,
                    'explanation' => $question->explanation,
                    'correct_option_text' => $correctOption?->option_text,
                ];
            }

            $score = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
            $passed = $score >= $quiz->passing_score;

            $attempt->update([
                'score' => $score,
                'status' => $passed ? 'completed' : 'failed',
                'completed_at' => now(),
            ]);

            if ($passed) {
                $this->gamificationService->awardXp(
                    user: $attempt->user,
                    points: 40,
                    sourceType: 'quiz',
                    sourceId: $quiz->id,
                    description: "Lulus kuis: {$quiz->title} (Skor {$score})"
                );
            }

            return [
                'attempt_id' => $attempt->id,
                'score' => $score,
                'passed' => $passed,
                'correct_count' => $correctCount,
                'total_questions' => $totalQuestions,
                'details' => $details,
            ];
        });
    }
}
