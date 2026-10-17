<?php

namespace Tests\Feature;

use App\Models\LearningModule;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\XpTransaction;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademyQuizTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_can_view_academy_index_and_module_detail(): void
    {
        $module = LearningModule::where('status', 'published')->firstOrFail();

        $this->get('/academy')->assertOk();
        $this->get("/academy/{$module->slug}")->assertOk();
    }

    public function test_user_can_complete_lesson_and_earn_xp_idempotently(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::firstOrFail();

        // 1. Mark lesson as complete
        $response = $this->actingAs($user)
            ->post("/lessons/{$lesson->id}/complete");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_lesson_progress', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => 'completed',
            'progress_percent' => 100,
        ]);

        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'source_type' => 'lesson',
            'source_id' => $lesson->id,
            'points' => 20,
        ]);

        $initialXpCount = XpTransaction::where('user_id', $user->id)->count();
        $this->assertEquals(1, $initialXpCount);

        // 2. Mark complete again (idempotency check)
        $repeatResponse = $this->actingAs($user)
            ->post("/lessons/{$lesson->id}/complete");

        $repeatResponse->assertSessionHas('success');

        $afterXpCount = XpTransaction::where('user_id', $user->id)->count();
        $this->assertEquals(1, $afterXpCount, 'XP must not be awarded repeatedly for the same lesson');
    }

    public function test_user_can_start_and_submit_quiz_with_scoring_and_xp(): void
    {
        $user = User::factory()->create();
        $quiz = Quiz::with('questions.options')->firstOrFail();

        // 1. Start quiz attempt
        $startResponse = $this->actingAs($user)
            ->postJson("/quizzes/{$quiz->id}/attempts");

        $startResponse->assertOk()
            ->assertJsonStructure(['attempt_id', 'status']);

        $attemptId = $startResponse->json('attempt_id');

        $attempt = QuizAttempt::find($attemptId);
        $this->assertNotNull($attempt);
        $this->assertEquals($user->id, $attempt->user_id);
        $this->assertEquals('in_progress', $attempt->status);

        // 2. Prepare correct answers payload
        $answers = [];
        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->firstWhere('is_correct', true);
            $answers[$question->id] = $correctOption ? $correctOption->id : $question->options->first()->id;
        }

        // 3. Submit attempt
        $submitResponse = $this->actingAs($user)
            ->postJson("/quiz-attempts/{$attemptId}/submit", [
                'answers' => $answers,
            ]);

        $submitResponse->assertOk()
            ->assertJsonPath('passed', true)
            ->assertJsonPath('score', 100);

        // 4. Verify attempt completion & XP award
        $attempt->refresh();
        $this->assertEquals('completed', $attempt->status);
        $this->assertEquals(100, $attempt->score);

        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'source_type' => 'quiz',
            'source_id' => $quiz->id,
            'points' => 40,
        ]);
    }
}
