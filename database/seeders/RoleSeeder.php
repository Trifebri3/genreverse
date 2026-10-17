<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Remaja',
                'slug' => 'remaja',
                'description' => 'Pengguna remaja umum untuk belajar, eksplorasi LifeQuest, dan berpartisipasi dalam komunitas.',
            ],
            [
                'name' => 'Duta GenRe',
                'slug' => 'ambassador',
                'description' => 'Duta Generasi Berencana resmi terverifikasi untuk mengadvokasi, membuat kampanye, dan menjawab Tanya Duta.',
            ],
            [
                'name' => 'Fasilitator / Pembina',
                'slug' => 'fasilitator',
                'description' => 'Pendamping PIK-R dan pembina remaja untuk memantau kegiatan dan bimbingan.',
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Moderator konten cerita, pertanyaan Tanya Duta, dan laporan masyarakat.',
            ],
            [
                'name' => 'Admin Wilayah',
                'slug' => 'admin_wilayah',
                'description' => 'Pengelola kegiatan, kampanye, dan verifikasi Duta dalam cakupan wilayah provinsi/kabupaten.',
            ],
            [
                'name' => 'Admin Nasional',
                'slug' => 'admin_nasional',
                'description' => 'Pengelola kurikulum edukasi, modul nasional, kampanye nasional, dan analitik dampak.',
            ],
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Akses sistem penuh, konfigurasi teknis, audit log, dan keamanan.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
