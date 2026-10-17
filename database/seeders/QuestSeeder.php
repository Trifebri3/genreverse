<?php

namespace Database\Seeders;

use App\Models\Quest;
use App\Models\QuestChoice;
use App\Models\QuestStage;
use Illuminate\Database\Seeder;

class QuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quest = Quest::firstOrCreate(
            ['slug' => 'dilema-lingkaran-teman-sebaya'],
            [
                'title' => 'LifeQuest: Dilema di Persimpangan Remaja',
                'description' => 'Simulasi interaktif menghadapi tekanan teman sebaya, godaan perilaku berisiko, dan seni mengambil keputusan bertanggung jawab demi masa depanmu.',
                'version' => 1,
                'difficulty' => 'medium',
                'xp_reward' => 75,
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        // Stage 1: Permulaan
        $stage1 = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_1'],
            [
                'title' => 'Ajakan Misterius Sepulang Sekolah',
                'story_content' => "Jam sekolah baru saja usai. Kamu berencana langsung pulang untuk menyelesaikan tugas persiapan ujian dan tugas kelompok PIK-R. Tiba-tiba, Doni dan geng populernya menghampirimu di gerbang sekolah.\n\n'Hei! Jangan kaku amat jadi orang. Kita mau kumpul di tempat rahasia belakang ruko kosong. Ada hal seru yang cuma anak pemberani yang berani coba. Kalau kamu gak ikut, berarti kamu penakut dan gak usah main sama kita lagi!'\n\nTatapan mereka menuntut jawaban seketika. Bagaimana kamu merespons?",
                'is_ending' => false,
                'sort_order' => 1,
            ]
        );

        // Stage 2A: Mengikuti ajakan karena takut dijauhi
        $stage2A = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_2a'],
            [
                'title' => 'Di Belakang Ruko Kosong: Godaan Pertama',
                'story_content' => "Kamu memutuskan ikut karena cemas dianggap penakut dan dikucilkan dari tongkrongan. Sesampainya di sana, suasananya redup dan tertutup. Doni mengeluarkan bungkusan rokok elektrik ilegal dan beberapa botol minuman beralkohol oplosan.\n\n'Nih, buktiin kalau kamu udah dewasa dan layak jadi bagian dari sirkel kita!' kata Doni sambil menyodorkannya ke depan wajahmu. Jantungmu berdegup kencang.",
                'is_ending' => false,
                'sort_order' => 2,
            ]
        );

        // Stage 2B: Berani berkata tidak secara asertif
        $stage2B = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_2b'],
            [
                'title' => 'Ketegasan yang Mengubah Suasana',
                'story_content' => "Dengan tatapan tenang dan nada suara mantap, kamu menjawab: 'Sori Don, sore ini aku ada komitmen tugas yang harus selesai. Aku tetap hargai ajakan kalian, tapi kalau urusan nongkrong di tempat seperti itu aku gak bisa ikut.'\n\nDoni sempat tertegun. Dua teman lainnya di belakang Doni tampak mengangguk pelan, seolah mereka sebenarnya juga ragu tapi tak seberani kamu untuk berkata tidak.",
                'is_ending' => false,
                'sort_order' => 3,
            ]
        );

        // Stage 3A: Ending Negatif / Peringatan
        $stage3A = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_3a_ending'],
            [
                'title' => 'Hasil Refleksi: Terjebak Konsekuensi Berbahaya',
                'story_content' => "Kamu mengalah dan mencoba barang tersebut demi pengakuan semu. Beberapa menit kemudian, razia petugas ketertiban lingkungan datang. Kamu terjebak, orang tuamu dipanggil, dan reputasi yang kamu bangun tercoreng seketika.\n\n### Pesan Edukasi GenRe:\nPengakuan teman yang menuntutmu merusak diri sendiri bukanlah pertemanan sejati. Berani menolak di awal membutuhkan keberanian besar, namun jauh lebih ringan dibanding menanggung penyesalan jangka panjang.",
                'is_ending' => true,
                'sort_order' => 4,
            ]
        );

        // Stage 3B: Ending Positif / Sahabat Berprinsip
        $stage3B = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_3b_ending'],
            [
                'title' => 'Hasil Refleksi: Menjadi Tuan Atas Diri Sendiri',
                'story_content' => "Kamu memilih pergi dan menolak dengan tegas saat disodori zat terlarang. Keesokan harinya di sekolah, salah satu teman Doni diam-diam menemuimu dan berkata: 'Keren kamu kemarin bisa berani nolak. Jujur aku sebenarnya terpaksa ikut karena takut.'\n\n### Pesan Edukasi GenRe:\nKetegasan asertifmu tidak hanya menyelamatkan masa depanmu, tetapi juga menjadi teladan (*peer role model*) bagi teman sebaya yang membutuhkan keberanian serupa. Kamu adalah representasi Remaja GenRe sejati!",
                'is_ending' => true,
                'sort_order' => 5,
            ]
        );

        // Stage 3C: Ending Kemenangan Karakter
        $stage3C = QuestStage::firstOrCreate(
            ['quest_id' => $quest->id, 'stage_key' => 'stage_3c_ending'],
            [
                'title' => 'Hasil Refleksi: Pengaruh Positif Pemimpin Sebaya',
                'story_content' => "Kamu pulang tepat waktu dan menyelesaikan tugas belajarmu dengan nilai terbaik. Beberapa hari kemudian, teman-temanmu justru menaruh hormat karena kamu konsisten berprinsip dan tidak mudah disetir orang lain.\n\n### Pesan Edukasi GenRe:\nHarga diri seorang remaja tidak dinilai dari kepatuhan buta pada tren yang salah, melainkan dari integritas menjaga komitmen dan masa depan yang direncanakan secara matang.",
                'is_ending' => true,
                'sort_order' => 6,
            ]
        );

        // Choices for Stage 1
        QuestChoice::firstOrCreate(
            ['stage_id' => $stage1->id, 'sort_order' => 1],
            [
                'choice_text' => 'Mengiyakan ajakan karena takut dikucilkan dan ingin dianggap keren.',
                'next_stage_id' => $stage2A->id,
                'feedback' => 'Kamu memilih menyerah pada tekanan kelompok (*compliance*). Mari lihat ke mana jalan ini membawamu.',
                'impact_summary' => 'Prioritas pribadi terabaikan, masuk ke lingkungan berisiko.',
            ]
        );

        QuestChoice::firstOrCreate(
            ['stage_id' => $stage1->id, 'sort_order' => 2],
            [
                'choice_text' => 'Menolak secara asertif, ramah, namun tegas dan tetap berpegang pada komitmen tugas.',
                'next_stage_id' => $stage2B->id,
                'feedback' => 'Pilihan berani! Komunikasi asertif adalah keterampilan hidup utama remaja GenRe.',
                'impact_summary' => 'Integritas terjaga, melindungi diri dari hal spekulatif.',
            ]
        );

        // Choices for Stage 2A
        QuestChoice::firstOrCreate(
            ['stage_id' => $stage2A->id, 'sort_order' => 1],
            [
                'choice_text' => 'Menerima dan mencoba zat tersebut sedikit demi solidaritas tongkrongan.',
                'next_stage_id' => $stage3A->id,
                'feedback' => 'Kompromi pada hal berisiko fatal sering kali berujung pada konsekuensi di luar kendalimu.',
                'impact_summary' => 'Terjebak masalah disiplin dan bahaya kesehatan.',
            ]
        );

        QuestChoice::firstOrCreate(
            ['stage_id' => $stage2A->id, 'sort_order' => 2],
            [
                'choice_text' => 'Sadar bahaya, langsung pamit undur diri dan menolak memegang barang tersebut.',
                'next_stage_id' => $stage3B->id,
                'feedback' => 'Masih ada rem darurat dalam dirimu! Menghentikan kesalahan sedini mungkin adalah keputusan bijak.',
                'impact_summary' => 'Mampu menarik diri sebelum situasi memburuk.',
            ]
        );

        // Choices for Stage 2B
        QuestChoice::firstOrCreate(
            ['stage_id' => $stage2B->id, 'sort_order' => 1],
            [
                'choice_text' => 'Lanjutkan perjalanan pulang dan fokus pada target impian masa depan.',
                'next_stage_id' => $stage3C->id,
                'feedback' => 'Fokus pada tujuan jangka panjang (*future-oriented*) menjauhkanmu dari distraksi merusak.',
                'impact_summary' => 'Mendapatkan respek alami dan hasil akademik optimal.',
            ]
        );
    }
}
