<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Models\Event;
use App\Models\Question;
use App\Models\Region;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $duta = User::where('email', 'duta@genreverse.id')->first();
        $admin = User::where('email', 'admin@genreverse.id')->first();
        $remaja = User::where('email', 'remaja@genreverse.id')->first();
        $jabar = Region::where('code', 'ID-JB')->first() ?? Region::first();
        $jakarta = Region::where('code', 'ID-JK')->first() ?? Region::first();

        // 1. Campaign: Aksi Nyata Cegah Stunting & Anemia
        $campaign1 = Campaign::firstOrCreate(
            ['slug' => 'remaja-sehat-bebas-anemia-cegah-stunting'],
            [
                'creator_id' => $duta ? $duta->id : 1,
                'region_id' => $jakarta->id,
                'title' => 'Gerakan Remaja Bebas Anemia: Menuju Generasi Emas Bebas Stunting',
                'issue_category' => 'kesehatan_reproduksi',
                'problem_statement' => 'Tingginya angka anemia pada remaja putri (mencapai 32% di tingkat sekolah) memicu risiko lingkaran stunting pada generasi masa depan.',
                'objective' => 'Mendistribusikan Tablet Tambah Darah (TTD) serta mengedukasi 500+ siswi SMP-SMA tentang gizi seimbang kaya protein hewani.',
                'target_audience' => 'Remaja putri usia 12-18 tahun dan pengurus PIK-Remaja sekolah.',
                'call_to_action' => 'Ayo minum 1 Tablet Tambah Darah setiap hari Jumat dan catat progresmu di aplikasi!',
                'planned_start' => now()->subDays(15),
                'planned_end' => now()->addDays(15),
                'status' => 'published',
                'reviewed_by' => $admin ? $admin->id : 1,
                'published_at' => now()->subDays(14),
            ]
        );

        // Campaign 2: Aksara Berdaya - Suarakan Remaja Resilien
        $campaign2 = Campaign::firstOrCreate(
            ['slug' => 'aksara-berdaya-remaja-resilien-cegah-pernikahan-dini'],
            [
                'creator_id' => $duta ? $duta->id : 1,
                'region_id' => $jabar->id,
                'title' => 'Aksara Berdaya: Suarakan Aspirasi Remaja Resilien & Stop Pernikahan Dini',
                'issue_category' => 'pendewasaan_usia_perkawinan',
                'problem_statement' => 'Tantangan perkawinan anak dan putus sekolah di kalangan remaja pedesaan yang menghambat potensi masa depan.',
                'objective' => 'Memberdayakan 300+ remaja melalui ruang advokasi digital Aksara Berdaya untuk menyuarakan aspirasi dan membangun resiliensi diri.',
                'target_audience' => 'Siswa SMP, SMA, dan karang taruna se-Jawa Barat.',
                'call_to_action' => 'Suarakan asamu dan buat karya advokasi digital bersama Duta GenRe!',
                'planned_start' => now()->subDays(10),
                'planned_end' => now()->addDays(20),
                'status' => 'published',
                'reviewed_by' => $admin ? $admin->id : 1,
                'published_at' => now()->subDays(8),
            ]
        );

        // Campaign Report verified
        CampaignReport::firstOrCreate(
            ['campaign_id' => $campaign1->id],
            [
                'submitted_by' => $duta ? $duta->id : 1,
                'activity_date' => now()->subDays(5),
                'summary' => 'Telah terlaksana sosialisasi luring di SMAN 70 Jakarta dan SMAN 6 Jakarta dengan total partisipasi 340 siswi. Seluruh peserta mendapatkan paket TTD dan panduan gizi GenRe.',
                'claimed_participants' => 340,
                'verified_participants' => 340,
                'verification_status' => 'verified',
                'reviewed_by' => $admin ? $admin->id : 1,
                'reviewed_at' => now()->subDays(2),
                'notes' => 'Dokumentasi kegiatan dan presensi lengkap terverifikasi.',
            ]
        );

        // 2. Events: Webinar & Jambore
        Event::firstOrCreate(
            ['title' => 'GenRe National Youth Summit 2026: Kenali Diri, Rancang Masa Depan'],
            [
                'organizer_id' => $admin ? $admin->id : 1,
                'region_id' => $jakarta->id,
                'description' => 'Konferensi virtual remaja se-Indonesia membahas keterampilan hidup abad 21, kesehatan mental, kepemimpinan sebaya, dan peluncuran inisiatif LifeQuest.',
                'location_label' => 'Live Streaming YouTube & Zoom GenRe Indonesia',
                'is_online' => true,
                'starts_at' => now()->addDays(7)->setTime(9, 0),
                'ends_at' => now()->addDays(7)->setTime(12, 30),
                'capacity' => 1000,
                'status' => 'published',
            ]
        );

        Event::firstOrCreate(
            ['title' => 'Workshop Peer Educator: Asertif Menghadapi Tekanan Sebaya'],
            [
                'organizer_id' => $duta ? $duta->id : 1,
                'region_id' => $jabar->id,
                'description' => 'Pelatihan tatap muka bagi konselor dan pendidik sebaya PIK-R tentang konseling tanpa menghakimi dan simulasi interaktif LifeQuest.',
                'location_label' => 'Aula BKKBN Perwakilan Jawa Barat, Bandung',
                'is_online' => false,
                'starts_at' => now()->addDays(14)->setTime(8, 30),
                'ends_at' => now()->addDays(14)->setTime(15, 0),
                'capacity' => 80,
                'status' => 'published',
            ]
        );

        // 3. Questions & Answers (Tanya Duta)
        $q1 = Question::firstOrCreate(
            ['public_reference' => 'QST-2026-001'],
            [
                'user_id' => $remaja ? $remaja->id : 1,
                'category' => 'Relasi Sehat',
                'question_body' => 'Kak, pacarku sering meminta password akun media sosialku dan melarangku berteman dengan teman sekelas. Katanya itu bukti cinta dan komitmen. Apakah itu hal wajar atau tanda hubungan tidak sehat?',
                'is_anonymous' => true,
                'status' => 'answered',
                'assigned_to' => $duta ? $duta->id : 1,
                'reviewed_by' => $admin ? $admin->id : 1,
            ]
        );

        Answer::firstOrCreate(
            ['question_id' => $q1->id],
            [
                'author_id' => $duta ? $duta->id : 1,
                'answer_body' => "Halo Sahabat GenRe! Terima kasih sudah berani bercerita. \n\nPerlu diingat: **Cinta yang sehat berlandaskan kepercayaan dan rasa hormat, bukan kontrol atau kepemilikan.**\n\nMeminta kata sandi pribadi dan membatasi lingkaran pertemanan adalah bentuk perilaku posesif dan pengontrolan (*controlling behavior*), yang merupakan salah satu tanda utama **Toxic Relationship** (*Red Flag*). Setiap orang memiliki hak atas privasi dan relasi sosial yang sehat di luar pasangan.\n\nSaran langkah yang bisa kamu ambil:\n1. Bicarakan batasan diri (*personal boundaries*) secara jujur dan tegas.\n2. Jika ia marah atau mengancam, ingatlah bahwa keselamatan emosionalmu adalah prioritas utama.\n3. Jangan ragu berdiskusi dengan guru BK atau konselor sebaya PIK-R.",
                'referral_resources' => 'SAPA GenRe: Layanan Konseling Sahabat Sebaya GenRe & Halo Kemenkes 1500-567',
                'status' => 'published',
                'reviewed_by' => $admin ? $admin->id : 1,
                'published_at' => now()->subDay(),
            ]
        );

        // 4. Moderated Stories (Cerita Kita)
        Story::firstOrCreate(
            ['title' => 'Dari Korban Bullying Menjadi Penggerak PIK-R di Sekolah'],
            [
                'author_id' => $remaja ? $remaja->id : 1,
                'body' => "Dulu di awal masuk SMP, aku adalah anak yang sangat pemalu dan sering menjadi sasaran ledekan teman-teman karena penampilanku. Rasa percaya diriku sempat jatuh ke titik nol.\n\nTitik balik terjadi ketika kakak-kakak Duta GenRe datang ke sekolah kami membawakan materi 'Kenali Diri'. Untuk pertama kalinya, aku merasa didengarkan tanpa dihakimi. Aku memberanikan diri bergabung dengan Pusat Informasi dan Konseling Remaja (PIK-R).\n\nDi sana, aku belajar public speaking, komunikasi asertif, dan manajemen emosi. Hari ini, aku bangga bisa berdiri di depan ratusan adik kelas sebagai konselor sebaya. Pesanku untuk semua remaja di luar sana: Jangan biarkan masa lalumu mendikte sebesar apa masa depan yang bisa kamu ciptakan!",
                'publication_mode' => 'pseudonym',
                'pen_name' => 'Bintang Harapan',
                'status' => 'published',
                'reviewed_by' => $admin ? $admin->id : 1,
                'published_at' => now()->subDays(3),
            ]
        );
    }
}
