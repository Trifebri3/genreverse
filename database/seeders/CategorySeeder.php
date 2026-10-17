<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pengenalan Diri & Life Skills',
                'slug' => 'pengenalan-diri-life-skills',
                'description' => 'Mengenali potensi diri, penetapan nilai hidup, manajemen waktu, dan resiliensi remaja.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kesehatan Remaja & Reproduksi',
                'slug' => 'kesehatan-remaja-reproduksi',
                'description' => 'Pemahaman ilmiah, higienitas organ reproduksi, pubertas, gizi seimbang, dan pencegahan anemia/stunting.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Perencanaan Berkeluarga & PUP',
                'slug' => 'perencanaan-berkeluarga-pup',
                'description' => 'Pendewasaan Usia Perkawinan (PUP), kesiapan fisik, mental, finansial, dan merancang masa depan matang.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Relasi Sehat & Batasan Diri',
                'slug' => 'relasi-sehat-batasan-diri',
                'description' => 'Pola hubungan sehat (healthy relationships), persetujuan (consent), batasan pribadi, dan anti kekerasan.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Kesehatan Mental & Regulasi Emosi',
                'slug' => 'kesehatan-mental-emosi',
                'description' => 'Manajemen stres, mengenali tanda kecemasan, mengatasi burnout sekolah, dan teknik self-care.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Literasi Digital & Peer Pressure',
                'slug' => 'literasi-digital-peer-pressure',
                'description' => 'Menghadapi tekanan sosial sebaya, etika bermedia sosial, verifikasi hoaks, dan keamanan jejak digital.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Kepemimpinan & Advokasi Sosial',
                'slug' => 'kepemimpinan-advokasi-sosial',
                'description' => 'Public speaking, merancang proyek sosial, menggerakkan teman sebaya, dan kolaborasi berdampak.',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
