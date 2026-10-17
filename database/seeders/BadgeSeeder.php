<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'code' => 'first_step',
                'name' => 'Langkah Pertama',
                'description' => 'Menyelesaikan modul pembelajaran GenRe Academy pertama kali.',
                'icon_path' => 'Compass',
                'criteria' => ['type' => 'lessons_completed', 'threshold' => 1],
                'is_active' => true,
            ],
            [
                'code' => 'quiz_master',
                'name' => 'Jawara Kuis GenRe',
                'description' => 'Mencapai skor kuis kelulusan di atas 85% dengan pemahaman prima.',
                'icon_path' => 'Award',
                'criteria' => ['type' => 'quiz_score', 'threshold' => 85],
                'is_active' => true,
            ],
            [
                'code' => 'quest_explorer',
                'name' => 'Penakluk LifeQuest',
                'description' => 'Menuntaskan simulasi pengambilan keputusan LifeQuest dengan refleksi bijak.',
                'icon_path' => 'Gamepad2',
                'criteria' => ['type' => 'quest_completed', 'threshold' => 1],
                'is_active' => true,
            ],
            [
                'code' => 'advocacy_pioneer',
                'name' => 'Pelopor Aksi Sosial',
                'description' => 'Berpartisipasi aktif dalam kampanye advokasi remaja nasional.',
                'icon_path' => 'Megaphone',
                'criteria' => ['type' => 'campaign_involved', 'threshold' => 1],
                'is_active' => true,
            ],
            [
                'code' => 'future_planner',
                'name' => 'Perencana Masa Depan',
                'description' => 'Menyelesaikan modul Pendewasaan Usia Perkawinan (PUP) & 8 Fungsi Keluarga.',
                'icon_path' => 'Sparkles',
                'criteria' => ['type' => 'module_pup_completed', 'threshold' => 1],
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['code' => $badge['code']], $badge);
        }
    }
}
