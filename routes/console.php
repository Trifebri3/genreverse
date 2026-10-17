<?php

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('setup:sole-admin', function () {
    // 1. Create or update King Nasar as sole admin
    $admin = User::updateOrCreate(
        ['email' => 'kingnasar@siyota.org'],
        [
            'name' => 'King Nasar',
            'password' => Hash::make('Kingnasar'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
    );

    $superRole = Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);
    $nasionalRole = Role::firstOrCreate(['slug' => 'admin_nasional'], ['name' => 'Admin Nasional']);

    $admin->roles()->sync([$superRole->id, $nasionalRole->id]);

    Profile::updateOrCreate(
        ['user_id' => $admin->id],
        [
            'display_name' => 'King Nasar',
            'bio' => 'Administrator Utama Publikasi & Konten GENREVERSE - Siyota Foundation',
            'preferred_language' => 'id',
        ]
    );

    // 2. Remove other admin users/roles so there is ONLY 1 admin in the entire system
    $otherAdmins = User::where('id', '!=', $admin->id)->get();
    foreach ($otherAdmins as $other) {
        $other->roles()->detach([$superRole->id, $nasionalRole->id]);
    }

    $oldAdmin = User::where('email', 'admin@genreverse.id')->first();
    if ($oldAdmin) {
        $oldAdmin->delete();
    }

    $this->info("Sole Administrator active: {$admin->email} ({$admin->name}) with password: Kingnasar");
})->purpose('Setup King Nasar as the single administrator');

