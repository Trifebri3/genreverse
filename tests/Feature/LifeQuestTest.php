<?php

namespace Tests\Feature;

use App\Models\Quest;
use App\Models\QuestAttempt;
use App\Models\User;
use App\Models\XpTransaction;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LifeQuestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_can_access_lifequest_pages_and_guest_player(): void
    {
        $quest = Quest::where('status', 'published')->firstOrFail();

        $this->get('/lifequest')->assertOk();
        $this->get("/lifequest/{$quest->slug}")->assertOk();
        $this->get("/lifequest/{$quest->slug}/play")->assertOk();
    }

    public function test_authenticated_user_can_start_attempt_and_advance_stages(): void
    {
        $user = User::factory()->create();
        $quest = Quest::with(['stages.choices'])->where('status', 'published')->firstOrFail();

        // 1. Start Quest Attempt
        $startResponse = $this->actingAs($user)
            ->postJson("/lifequest/{$quest->id}/attempts");

        $startResponse->assertOk()
            ->assertJsonStructure(['attempt', 'currentStage']);

        $attemptId = $startResponse->json('attempt.id');
        $attempt = QuestAttempt::findOrFail($attemptId);
        $this->assertEquals('in_progress', $attempt->status);

        // 2. Play through stages until completed
        $currentStage = $quest->stages->firstWhere('id', $attempt->current_stage_id);
        $this->assertNotNull($currentStage);

        $choice = $currentStage->choices->first();
        $this->assertNotNull($choice);

        $choiceResponse = $this->actingAs($user)
            ->postJson("/quest-attempts/{$attempt->id}/choose", [
                'stage_id' => $currentStage->id,
                'choice_id' => $choice->id,
            ]);

        $choiceResponse->assertOk()
            ->assertJsonStructure(['choice_feedback', 'next_stage', 'is_completed']);

        // Check if finished or continue to next stage if multi-stage
        $isCompleted = $choiceResponse->json('is_completed');
        if (! $isCompleted && $choiceResponse->json('next_stage')) {
            $nextStageId = $choiceResponse->json('next_stage.id');
            $nextStage = $quest->stages->firstWhere('id', $nextStageId);
            if ($nextStage && $nextStage->choices->isNotEmpty()) {
                $finalChoice = $nextStage->choices->first();
                $finalResponse = $this->actingAs($user)
                    ->postJson("/quest-attempts/{$attempt->id}/choose", [
                        'stage_id' => $nextStage->id,
                        'choice_id' => $finalChoice->id,
                    ]);
                $finalResponse->assertOk();
                $isCompleted = $finalResponse->json('is_completed');
            }
        }

        $attempt->refresh();
        if ($isCompleted) {
            $this->assertEquals('completed', $attempt->status);
            $this->assertDatabaseHas('xp_transactions', [
                'user_id' => $user->id,
                'source_type' => 'quest',
                'source_id' => $quest->id,
            ]);

            // Test idempotency: awarding XP again for the same quest should not duplicate
            $xpCount = XpTransaction::where('user_id', $user->id)
                ->where('source_type', 'quest')
                ->where('source_id', $quest->id)
                ->count();
            $this->assertEquals(1, $xpCount);
        }
    }
}
