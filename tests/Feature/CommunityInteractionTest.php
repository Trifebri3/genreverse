<?php

namespace Tests\Feature;

use App\Models\ContentReport;
use App\Models\Event;
use App\Models\Question;
use App\Models\Role;
use App\Models\Story;
use App\Models\User;
use App\Models\XpTransaction;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityInteractionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_can_register_for_event_and_earn_xp(): void
    {
        $user = User::factory()->create();
        $event = Event::where('status', 'published')->firstOrFail();

        // 1. Register for event
        $response = $this->actingAs($user)
            ->post("/community/events/{$event->id}/register");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'registration_status' => 'registered',
        ]);

        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'source_type' => 'event',
            'source_id' => $event->id,
            'points' => 15,
        ]);

        // 2. Register again (idempotency check)
        $repeatResponse = $this->actingAs($user)
            ->post("/community/events/{$event->id}/register");

        $repeatResponse->assertSessionHas('success');

        $xpCount = XpTransaction::where('user_id', $user->id)
            ->where('source_type', 'event')
            ->where('source_id', $event->id)
            ->count();
        $this->assertEquals(1, $xpCount, 'Event XP must only be awarded once per event registration');
    }

    public function test_user_can_submit_tanya_duta_and_duta_can_answer_after_moderation(): void
    {
        $user = User::factory()->create();
        $duta = User::factory()->create();
        $ambassadorRole = Role::where('slug', 'ambassador')->first();
        $duta->roles()->attach($ambassadorRole);

        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        // 1. Submit Question
        $askResponse = $this->actingAs($user)->post('/ask', [
            'category' => 'Relasi Sehat & Batasan Diri',
            'question_body' => 'Bagaimana cara menolak ajakan pacar yang meminta hal melanggar batas pribadi secara asertif tanpa merasa bersalah?',
            'is_anonymous' => true,
        ]);

        $askResponse->assertSessionHas('success');

        $question = Question::where('user_id', $user->id)->first();
        $this->assertNotNull($question);
        $this->assertEquals('pending_review', $question->status);
        $this->assertTrue((bool) $question->is_anonymous);

        // 2. Admin moderates and approves question
        $modResponse = $this->actingAs($admin)
            ->post("/admin/moderation/questions/{$question->id}/review", [
                'approved' => true,
            ]);

        $modResponse->assertSessionHas('success');

        $question->refresh();
        $this->assertEquals('approved', $question->status);

        // 3. Duta answers the question
        $answerResponse = $this->actingAs($duta)
            ->post("/ask/{$question->id}/answer", [
                'answer_body' => 'Hai Sahabat GenRe! Menolak hal yang tidak nyaman adalah hak mutlakmu. Gunakan teknik I-Statement: "Aku merasa tidak nyaman dan aku memilih untuk menjaga batasan ini demi masa depan kita bersama."',
                'referral_resources' => 'Layanan Konseling Sebaya GenRe & Puspaga',
            ]);

        $answerResponse->assertSessionHas('success');

        $question->refresh();
        $this->assertEquals('answered', $question->status);
        $this->assertEquals($duta->id, $question->assigned_to);

        $this->assertDatabaseHas('answers', [
            'question_id' => $question->id,
            'author_id' => $duta->id,
            'status' => 'published',
        ]);
    }

    public function test_story_submission_and_moderation_flow(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        // 1. User submits story
        $storyResponse = $this->actingAs($user)->post('/stories', [
            'title' => 'Dari Introvert Menjadi Konselor Sebaya Berani Berbicara',
            'body' => 'Dulu saya sangat pemalu dan takut berbicara di depan umum. Melalui pelatihan Pusat Informasi dan Konseling Remaja (PIK-R), saya belajar mengenali potensi diri dan kini aktif mengedukasi teman sebaya tentang pencegahan stunting dan anemia.',
            'publication_mode' => 'public_name',
        ]);

        $storyResponse->assertSessionHas('success');

        $story = Story::where('author_id', $user->id)->first();
        $this->assertNotNull($story);
        $this->assertEquals('under_review', $story->status);

        // 2. Admin approves story
        $modResponse = $this->actingAs($admin)
            ->post("/admin/moderation/stories/{$story->id}/review", [
                'approved' => true,
            ]);

        $modResponse->assertSessionHas('success');

        $story->refresh();
        $this->assertEquals('published', $story->status);
        $this->assertEquals($admin->id, $story->reviewed_by);
    }

    public function test_content_reporting_and_moderation_resolution(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        // 1. Report content
        $reportResponse = $this->actingAs($user)->post('/content-reports', [
            'content_type' => 'comment',
            'content_id' => 999,
            'reason' => 'Cyberbullying / Pelecehan',
            'description' => 'Komentar bernada ujaran kebencian terhadap peserta lain.',
        ]);

        $reportResponse->assertSessionHas('success');

        $report = ContentReport::where('content_id', 999)->first();
        $this->assertNotNull($report);
        $this->assertEquals('pending', $report->status);

        // 2. Admin resolves report
        $resolveResponse = $this->actingAs($admin)
            ->post("/admin/moderation/reports/{$report->id}/resolve", [
                'action' => 'hidden',
                'reason' => 'Konten melanggar pedoman komunitas perlindungan remaja.',
            ]);

        $resolveResponse->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals('action_taken', $report->status);

        $this->assertDatabaseHas('moderation_actions', [
            'report_id' => $report->id,
            'moderator_id' => $admin->id,
            'action' => 'hidden',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $admin->id,
            'action' => 'moderation.report_resolved',
            'subject_id' => $report->id,
        ]);
    }
}
