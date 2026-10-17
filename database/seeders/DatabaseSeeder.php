<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RegionSeeder::class,
            CategorySeeder::class,
            DemoUserSeeder::class,
            BadgeSeeder::class,
            LearningModuleSeeder::class,
            QuestSeeder::class,
            CommunitySeeder::class,
        ]);
    }
}
