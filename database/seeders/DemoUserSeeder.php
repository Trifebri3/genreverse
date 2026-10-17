<?php

namespace Database\Seeders;

use App\Models\Ambassador;
use App\Models\Profile;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRegionScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalRegion = Region::where('code', 'ID')->first();
        $jakartaRegion = Region::where('code', 'ID-JK')->first();
        $jabarRegion = Region::where('code', 'ID-JB')->first();

        // 1. Single Administrator: King Nasar
        $admin = User::updateOrCreate(
            ['email' => 'kingnasar@siyota.org'],
            [
                'name' => 'King Nasar',
                'password' => Hash::make('Kingnasar'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([
            Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin'])->id,
            Role::firstOrCreate(['slug' => 'admin_nasional'], ['name' => 'Admin Nasional'])->id,
        ]);
        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'display_name' => 'King Nasar',
                'bio' => 'Administrator Utama Publikasi & Konten GENREVERSE - Siyota Foundation',
                'preferred_language' => 'id',
            ]
        );

        // 2. Duta GenRe Terverifikasi
        $duta = User::firstOrCreate(
            ['email' => 'duta@genreverse.id'],
            [
                'name' => 'Anindya Putri, S.Sos',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $duta->roles()->syncWithoutDetaching([
            Role::where('slug', 'ambassador')->first()->id,
        ]);
        Profile::updateOrCreate(
            ['user_id' => $duta->id],
            [
                'display_name' => 'Anindya (Duta GenRe Putri 2025)',
                'bio' => 'Duta GenRe Indonesia. Berbagi edukasi kesehatan reproduksi, perencanaan masa depan, dan advokasi cegah stunting.',
                'preferred_language' => 'id',
            ]
        );
        Ambassador::updateOrCreate(
            ['user_id' => $duta->id],
            [
                'region_id' => $jakartaRegion ? $jakartaRegion->id : $nationalRegion->id,
                'organization_name' => 'Forum GenRe DKI Jakarta & BKKBN',
                'verification_status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()->subMonths(3),
                'valid_until' => now()->addYear(),
            ]
        );

        // 3. Moderator
        $moderator = User::firstOrCreate(
            ['email' => 'moderator@genreverse.id'],
            [
                'name' => 'Fadhil Rizqi (Moderator)',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $moderator->roles()->syncWithoutDetaching([
            Role::where('slug', 'moderator')->first()->id,
        ]);
        Profile::updateOrCreate(
            ['user_id' => $moderator->id],
            [
                'display_name' => 'Fadhil Mod',
                'bio' => 'Tim kurasi konten dan konseling sebaya GenReverse.',
                'preferred_language' => 'id',
            ]
        );

        // 4. Admin Wilayah
        $adminWilayah = User::firstOrCreate(
            ['email' => 'admin.wilayah@genreverse.id'],
            [
                'name' => 'Dewi Sartika (Admin Jabar)',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $adminWilayah->roles()->syncWithoutDetaching([
            Role::where('slug', 'admin_wilayah')->first()->id,
        ]);
        Profile::updateOrCreate(
            ['user_id' => $adminWilayah->id],
            [
                'display_name' => 'Admin GenRe Jawa Barat',
                'bio' => 'Koordinator Wilayah Forum GenRe Provinsi Jawa Barat.',
                'preferred_language' => 'id',
            ]
        );
        if ($jabarRegion) {
            UserRegionScope::firstOrCreate([
                'user_id' => $adminWilayah->id,
                'region_id' => $jabarRegion->id,
                'scope_type' => 'primary',
            ]);
        }

        // 5. Remaja Pengguna
        $remaja = User::firstOrCreate(
            ['email' => 'remaja@genreverse.id'],
            [
                'name' => 'Rian Hidayat',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $remaja->roles()->syncWithoutDetaching([
            Role::where('slug', 'remaja')->first()->id,
        ]);
        Profile::updateOrCreate(
            ['user_id' => $remaja->id],
            [
                'display_name' => 'Rian (Youth Explorer)',
                'bio' => 'Siswa SMA yang antusias belajar life skills dan aktif di PIK-R sekolah.',
                'birth_year' => 2008,
                'preferred_language' => 'id',
            ]
        );
    }
}
