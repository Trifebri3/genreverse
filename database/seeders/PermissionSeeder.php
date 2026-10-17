<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Education
            ['name' => 'View Public Modules', 'slug' => 'modules.view_public', 'group' => 'Education'],
            ['name' => 'Create Modules', 'slug' => 'modules.create', 'group' => 'Education'],
            ['name' => 'Edit Modules', 'slug' => 'modules.edit', 'group' => 'Education'],
            ['name' => 'Publish Modules', 'slug' => 'modules.publish', 'group' => 'Education'],
            ['name' => 'Delete Modules', 'slug' => 'modules.delete', 'group' => 'Education'],

            // Quests
            ['name' => 'View Quests', 'slug' => 'quests.view', 'group' => 'LifeQuest'],
            ['name' => 'Manage Quests', 'slug' => 'quests.manage', 'group' => 'LifeQuest'],

            // Advocacy / Campaigns
            ['name' => 'Create Campaign', 'slug' => 'campaigns.create', 'group' => 'Advocacy'],
            ['name' => 'Submit Campaign Report', 'slug' => 'campaigns.report', 'group' => 'Advocacy'],
            ['name' => 'Review Region Campaigns', 'slug' => 'campaigns.review_region', 'group' => 'Advocacy'],
            ['name' => 'Review National Campaigns', 'slug' => 'campaigns.review_national', 'group' => 'Advocacy'],
            ['name' => 'Verify Campaign Report', 'slug' => 'campaigns.verify_report', 'group' => 'Advocacy'],

            // Events
            ['name' => 'Register Events', 'slug' => 'events.register', 'group' => 'Community'],
            ['name' => 'Create Events', 'slug' => 'events.create', 'group' => 'Community'],
            ['name' => 'Manage Region Events', 'slug' => 'events.manage_region', 'group' => 'Community'],
            ['name' => 'Manage National Events', 'slug' => 'events.manage_national', 'group' => 'Community'],

            // Stories & Questions
            ['name' => 'Submit Story', 'slug' => 'stories.submit', 'group' => 'Community'],
            ['name' => 'Moderate Stories', 'slug' => 'stories.moderate', 'group' => 'Moderation'],
            ['name' => 'Ask Question', 'slug' => 'questions.ask', 'group' => 'Tanya Duta'],
            ['name' => 'Answer Question', 'slug' => 'questions.answer', 'group' => 'Tanya Duta'],
            ['name' => 'Moderate Questions', 'slug' => 'questions.moderate', 'group' => 'Moderation'],

            // Moderation & Audit
            ['name' => 'Report Content', 'slug' => 'content.report', 'group' => 'Moderation'],
            ['name' => 'Handle Moderation', 'slug' => 'content.moderate', 'group' => 'Moderation'],
            ['name' => 'Verify Ambassadors Region', 'slug' => 'ambassadors.verify_region', 'group' => 'Administration'],
            ['name' => 'Verify Ambassadors National', 'slug' => 'ambassadors.verify_national', 'group' => 'Administration'],
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'group' => 'Administration'],
            ['name' => 'View Audit Logs', 'slug' => 'audit.view', 'group' => 'Administration'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $perm) {
            $createdPermissions[$perm['slug']] = Permission::firstOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Attach permissions to roles
        $remaja = Role::where('slug', 'remaja')->first();
        if ($remaja) {
            $remaja->permissions()->sync([
                $createdPermissions['modules.view_public']->id,
                $createdPermissions['quests.view']->id,
                $createdPermissions['events.register']->id,
                $createdPermissions['stories.submit']->id,
                $createdPermissions['questions.ask']->id,
                $createdPermissions['content.report']->id,
            ]);
        }

        $ambassador = Role::where('slug', 'ambassador')->first();
        if ($ambassador) {
            $ambassador->permissions()->sync([
                $createdPermissions['modules.view_public']->id,
                $createdPermissions['quests.view']->id,
                $createdPermissions['campaigns.create']->id,
                $createdPermissions['campaigns.report']->id,
                $createdPermissions['events.register']->id,
                $createdPermissions['events.create']->id,
                $createdPermissions['stories.submit']->id,
                $createdPermissions['questions.ask']->id,
                $createdPermissions['questions.answer']->id,
                $createdPermissions['content.report']->id,
            ]);
        }

        $moderator = Role::where('slug', 'moderator')->first();
        if ($moderator) {
            $moderator->permissions()->sync([
                $createdPermissions['modules.view_public']->id,
                $createdPermissions['stories.moderate']->id,
                $createdPermissions['questions.moderate']->id,
                $createdPermissions['content.moderate']->id,
                $createdPermissions['content.report']->id,
            ]);
        }

        $adminWilayah = Role::where('slug', 'admin_wilayah')->first();
        if ($adminWilayah) {
            $adminWilayah->permissions()->sync([
                $createdPermissions['modules.view_public']->id,
                $createdPermissions['campaigns.review_region']->id,
                $createdPermissions['campaigns.verify_report']->id,
                $createdPermissions['events.manage_region']->id,
                $createdPermissions['ambassadors.verify_region']->id,
                $createdPermissions['content.moderate']->id,
            ]);
        }

        $adminNasional = Role::where('slug', 'admin_nasional')->first();
        if ($adminNasional) {
            $adminNasional->permissions()->sync(collect($createdPermissions)->pluck('id'));
        }

        $superAdmin = Role::where('slug', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(collect($createdPermissions)->pluck('id'));
        }
    }
}
