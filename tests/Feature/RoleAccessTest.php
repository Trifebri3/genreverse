<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_guest_can_access_all_public_landing_and_educational_pages(): void
    {
        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get('/privacy')->assertOk();
        $this->get('/academy')->assertOk();
        $this->get('/lifequest')->assertOk();
        $this->get('/advocacy')->assertOk();
        $this->get('/community/events')->assertOk();
        $this->get('/stories')->assertOk();
        $this->get('/ask')->assertOk();
        $this->get('/impact-map')->assertOk();
    }

    public function test_unauthenticated_user_cannot_access_user_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_remaja_user_cannot_access_administration_portal(): void
    {
        $user = User::factory()->create();
        $remajaRole = Role::where('slug', 'remaja')->first();
        $user->roles()->attach($remajaRole);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();

        $this->actingAs($user)
            ->get('/admin/campaigns')
            ->assertForbidden();

        $this->actingAs($user)
            ->get('/admin/moderation')
            ->assertForbidden();
    }

    public function test_admin_nasional_can_access_administration_portal(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('slug', 'admin_nasional')->first();
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/campaigns')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/moderation')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/audit-logs')
            ->assertOk();
    }
}
