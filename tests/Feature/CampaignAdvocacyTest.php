<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignAdvocacyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_duta_can_create_campaign_draft_and_submit_for_review(): void
    {
        $duta = User::factory()->create();
        $ambassadorRole = Role::where('slug', 'ambassador')->first();
        $duta->roles()->attach($ambassadorRole);

        $region = Region::where('is_active', true)->firstOrFail();

        // 1. Create campaign
        $response = $this->actingAs($duta)->post('/campaigns', [
            'title' => 'Gerakan Remaja Anti Perkawinan Anak di Jawa Barat',
            'region_id' => $region->id,
            'issue_category' => 'Pendewasaan Usia Perkawinan (PUP)',
            'problem_statement' => 'Tingginya angka dispensasi kawin di bawah umur 19 tahun di kalangan pelajar.',
            'objective' => 'Memberikan edukasi konsekuensi fisik, mental, dan ekonomi perkawinan dini.',
            'target_audience' => 'Remaja usia 13-18 tahun di 5 sekolah',
            'call_to_action' => 'Katakan tidak pada perkawinan dini dan selesaikan pendidikan dasar 12 tahun!',
            'planned_start' => now()->addDays(5)->toDateString(),
            'planned_end' => now()->addDays(20)->toDateString(),
        ]);

        $response->assertRedirect();

        $campaign = Campaign::where('title', 'Gerakan Remaja Anti Perkawinan Anak di Jawa Barat')->first();
        $this->assertNotNull($campaign);
        $this->assertEquals($duta->id, $campaign->creator_id);
        $this->assertEquals('draft', $campaign->status);

        // 2. Submit for review
        $submitResponse = $this->actingAs($duta)
            ->post("/campaigns/{$campaign->id}/submit");

        $submitResponse->assertSessionHas('success');

        $campaign->refresh();
        $this->assertEquals('in_review', $campaign->status);
    }

    public function test_moderator_or_admin_can_approve_campaign_and_publish_it(): void
    {
        $duta = User::factory()->create();
        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        $region = Region::where('is_active', true)->firstOrFail();

        $campaign = Campaign::create([
            'creator_id' => $duta->id,
            'region_id' => $region->id,
            'title' => 'Kampanye Generasi Remaja Ceria',
            'slug' => 'kampanye-generasi-remaja-ceria',
            'issue_category' => 'Kesehatan Mental',
            'problem_statement' => 'Banyak remaja merasa cemas berlebihan dan kurang ruang aman bercurhat.',
            'objective' => 'Membangun jejaring konseling sebaya ramah remaja.',
            'target_audience' => 'Siswa SMA dan SMK',
            'call_to_action' => 'Kenali emosimu dan beranilah mencari bantuan.',
            'status' => 'in_review',
        ]);

        // Non-admin attempt should be forbidden
        $this->actingAs($duta)
            ->post("/admin/campaigns/{$campaign->id}/review", [
                'approved' => true,
                'reason' => 'Bagus',
            ])
            ->assertForbidden();

        // Admin approves
        $reviewResponse = $this->actingAs($admin)
            ->post("/admin/campaigns/{$campaign->id}/review", [
                'approved' => true,
                'reason' => 'Rancangan sangat baik dan memenuhi standar keselamatan remaja.',
            ]);

        $reviewResponse->assertSessionHas('success');

        $campaign->refresh();
        $this->assertEquals('published', $campaign->status);
        $this->assertNotNull($campaign->published_at);
        $this->assertEquals($admin->id, $campaign->reviewed_by);

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $admin->id,
            'action' => 'campaign.approved',
            'subject_id' => $campaign->id,
        ]);
    }

    public function test_activity_reporting_and_impact_verification_workflow(): void
    {
        $duta = User::factory()->create();
        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        $region = Region::where('is_active', true)->firstOrFail();

        $campaign = Campaign::create([
            'creator_id' => $duta->id,
            'region_id' => $region->id,
            'title' => 'Workshop Relasi Sehat',
            'slug' => 'workshop-relasi-sehat',
            'issue_category' => 'Relasi Sehat',
            'problem_statement' => 'Kurangnya pemahaman batasan diri.',
            'objective' => 'Pemahaman consent dan anti kekerasan.',
            'target_audience' => 'Remaja umum',
            'call_to_action' => 'Jaga diri dan hargai teman.',
            'status' => 'published',
        ]);

        // 1. Duta submits activity report
        $reportResponse = $this->actingAs($duta)
            ->post("/campaigns/{$campaign->id}/reports", [
                'activity_date' => now()->toDateString(),
                'summary' => 'Workshop telah selesai dilaksanakan di Aula SMAN 1 dengan antusiasme tinggi.',
                'claimed_participants' => 85,
                'evidence_path' => 'https://drive.google.com/folder-dokumentasi-kegiatan',
            ]);

        $reportResponse->assertSessionHas('success');

        $report = CampaignReport::where('campaign_id', $campaign->id)->first();
        $this->assertNotNull($report);
        $this->assertEquals('pending', $report->verification_status);
        $this->assertEquals(85, $report->claimed_participants);

        // 2. Admin verifies report and confirms actual verified participants
        $verifyResponse = $this->actingAs($admin)
            ->post("/admin/campaign-reports/{$report->id}/verify", [
                'verified' => true,
                'verified_participants' => 80,
                'notes' => 'Presensi fisik dan dokumentasi valid untuk 80 peserta.',
            ]);

        $verifyResponse->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals('verified', $report->verification_status);
        $this->assertEquals(80, $report->verified_participants);
        $this->assertEquals($admin->id, $report->reviewed_by);

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $admin->id,
            'action' => 'campaign_report.verified',
            'subject_id' => $report->id,
        ]);
    }
}
