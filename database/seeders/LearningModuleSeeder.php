<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LearningModule;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@genreverse.id')->first();
        $authorId = $admin ? $admin->id : 1;

        $catKespro = Category::where('slug', 'kesehatan-remaja-reproduksi')->first();
        $catPup = Category::where('slug', 'perencanaan-berkeluarga-pup')->first();
        $catPressure = Category::where('slug', 'literasi-digital-peer-pressure')->first();

        // Module 1: Kesehatan Reproduksi Remaja
        if ($catKespro) {
            $mod1 = LearningModule::firstOrCreate(
                ['slug' => 'pubertas-kesehatan-reproduksi-remaja'],
                [
                    'category_id' => $catKespro->id,
                    'author_id' => $authorId,
                    'reviewed_by' => $authorId,
                    'title' => 'Transisi Pubertas & Kesehatan Reproduksi: Kenali Tubuhmu Secara Ilmiah',
                    'summary' => 'Pelajari perubahan fisik, hormonal, dan psikologis selama masa pubertas dengan panduan ilmiah yang aman, positif, dan terpercaya.',
                    'cover_path' => '/images/topic_kespro.jpg',
                    'difficulty' => 'beginner',
                    'estimated_minutes' => 20,
                    'status' => 'published',
                    'published_at' => now()->subDays(10),
                ]
            );

            // Lesson 1.1
            $l1 = Lesson::firstOrCreate(
                ['module_id' => $mod1->id, 'sort_order' => 1],
                [
                    'title' => 'Perubahan Fisik & Hormon: Mengapa Tubuhku Berubah?',
                    'content_type' => 'text',
                    'estimated_minutes' => 7,
                    'status' => 'published',
                    'content_body' => "### Selamat Datang di Masa Pubertas!\n\nPubertas adalah proses biologis alami di mana tubuh anak-anak bertransisi menjadi dewasa yang mampu bereproduksi. Proses ini dipicu oleh sinyal dari kelenjar hipofisis di otak yang memicu pelepasan hormon gonadotropin.\n\n#### 1. Perubahan pada Remaja Putri\n- **Hormon Utama:** Estrogen dan Progesteron.\n- **Tanda-tanda:** Pertumbuhan payudara (thelarche), perubahan proporsi pinggul, timbulnya rambut halus, dan terjadinya *menarche* (menstruasi pertama).\n- **Pesan Kunci:** Siklus menstruasi pada 1-2 tahun pertama sering kali belum teratur. Ini adalah adaptasi normal sistem endokrin tubuh.\n\n#### 2. Perubahan pada Remaja Putra\n- **Hormon Utama:** Testosteron.\n- **Tanda-tanda:** Pembesaran testis dan penis, suara yang membesar (*voice cracking* karena pembesaran laring), mimpi basah (*nocturnal emission*), serta pertumbuhan massa otot.\n\n#### 3. Kebersihan Pribadi (*Personal Hygiene*)\nPerubahan kelenjar keringat apokrin menyebabkan aroma tubuh lebih pekat dan risiko jerawat. Menjaga kebersihan harian dengan mandi teratur, mengganti pakaian dalam minimal 2x sehari berbahan katun, dan mencuci muka dengan sabun lembut adalah kunci utama kesehatan diri.",
                ]
            );

            // Lesson 1.2
            $l2 = Lesson::firstOrCreate(
                ['module_id' => $mod1->id, 'sort_order' => 2],
                [
                    'title' => 'Gizi Remaja: Mengapa Anemia Mengancam Masa Depan?',
                    'content_type' => 'text',
                    'estimated_minutes' => 8,
                    'status' => 'published',
                    'content_body' => "### Bahaya Tersembunyi: 5L (Lesu, Lemah, Letih, Lelah, Lalai)\n\nAnemia defisiensi besi adalah salah satu tantangan terbesar remaja Indonesia, terutama remaja putri karena kehilangan darah bulanan saat menstruasi dan pola makan yang kurang asupan zat besi hewani.\n\n#### Dampak Nyata Anemia pada Remaja:\n1. **Konsentrasi Menurun:** Pasokan oksigen ke otak berkurang, menyebabkan prestasi akademik dan fokus belajar merosot drastis.\n2. **Daya Tahan Tubuh Rapuh:** Remaja lebih mudah terserang infeksi dan lelah berkepanjangan.\n3. **Jangka Panjang (Siklus Stunting):** Remaja putri yang anemia kelak berisiko tinggi melahirkan bayi Berat Badan Lahir Rendah (BBLR) yang merupakan pemicu utama stunting.\n\n#### Solusi Nyata Program GenRe:\n- Konsumsi makanan kaya zat besi (hati ayam, daging sapi, ikan, kacang-kacangan, bayam hijau).\n- Minum **Tablet Tambah Darah (TTD)** 1 tablet setiap minggu secara rutin, disertai vitamin C untuk memaksimalkan penyerapan zat besi.\n- Hindari konsumsi teh atau kopi bersamaan saat makan makanan berzat besi.",
                ]
            );

            // Quiz for Module 1
            $quiz1 = Quiz::firstOrCreate(
                ['module_id' => $mod1->id, 'title' => 'Kuis Pemahaman Kesehatan Reproduksi & Nutrisi Remaja'],
                [
                    'passing_score' => 70,
                    'attempt_limit' => 3,
                    'status' => 'published',
                ]
            );

            // Question 1
            $q1 = QuizQuestion::firstOrCreate(
                ['quiz_id' => $quiz1->id, 'sort_order' => 1],
                [
                    'question_text' => 'Berapa frekuensi anjuran konsumsi Tablet Tambah Darah (TTD) bagi remaja putri untuk mencegah anemia?',
                    'question_type' => 'multiple_choice',
                    'explanation' => 'Kemenkes dan BKKBN merekomendasikan minum Tablet Tambah Darah (TTD) 1 tablet setiap minggu secara teratur untuk mencegah anemia.',
                ]
            );
            QuizOption::firstOrCreate(['question_id' => $q1->id, 'sort_order' => 1], ['option_text' => '1 tablet setiap hari seumur hidup', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q1->id, 'sort_order' => 2], ['option_text' => '1 tablet seminggu sekali secara rutin', 'is_correct' => true]);
            QuizOption::firstOrCreate(['question_id' => $q1->id, 'sort_order' => 3], ['option_text' => 'Hanya saat merasa pusing atau pingsan', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q1->id, 'sort_order' => 4], ['option_text' => 'Cukup 1 tablet setiap tahun', 'is_correct' => false]);

            // Question 2
            $q2 = QuizQuestion::firstOrCreate(
                ['quiz_id' => $quiz1->id, 'sort_order' => 2],
                [
                    'question_text' => 'Manakah minuman yang dapat menghambat penyerapan zat besi jika diminum bersamaan dengan makanan utama?',
                    'question_type' => 'multiple_choice',
                    'explanation' => 'Teh dan kopi mengandung senyawa tanin dan fitat yang mengikat zat besi sehingga sulit diserap oleh saluran pencernaan.',
                ]
            );
            QuizOption::firstOrCreate(['question_id' => $q2->id, 'sort_order' => 1], ['option_text' => 'Jus jeruk peras segar (Vitamin C)', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q2->id, 'sort_order' => 2], ['option_text' => 'Air putih hangat', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q2->id, 'sort_order' => 3], ['option_text' => 'Teh pekat atau kopi manis', 'is_correct' => true]);
            QuizOption::firstOrCreate(['question_id' => $q2->id, 'sort_order' => 4], ['option_text' => 'Air kelapa murni', 'is_correct' => false]);
        }

        // Module 2: Pendewasaan Usia Perkawinan (PUP)
        if ($catPup) {
            $mod2 = LearningModule::firstOrCreate(
                ['slug' => 'pendewasaan-usia-perkawinan-pup'],
                [
                    'category_id' => $catPup->id,
                    'author_id' => $authorId,
                    'reviewed_by' => $authorId,
                    'title' => 'Pendewasaan Usia Perkawinan (PUP): Siapkan Diri Sebelum Melangkah',
                    'summary' => 'Memahami batas usia ideal menikah menurut sains dan regulasi: 21 tahun untuk perempuan dan 25 tahun untuk laki-laki demi masa depan keluarga berkualitas.',
                    'cover_path' => '/images/academy_cover.jpg',
                    'difficulty' => 'intermediate',
                    'estimated_minutes' => 25,
                    'status' => 'published',
                    'published_at' => now()->subDays(5),
                ]
            );

            $l2_1 = Lesson::firstOrCreate(
                ['module_id' => $mod2->id, 'sort_order' => 1],
                [
                    'title' => 'Kenapa Harus 21 dan 25 Tahun? Alasan Medis & Psikososial',
                    'content_type' => 'text',
                    'estimated_minutes' => 10,
                    'status' => 'published',
                    'content_body' => "### Usia Ideal Menikah GenRe: 21 Tahun (Putri) & 25 Tahun (Putra)\n\nProgram Generasi Berencana (GenRe) menetapkan rekomendasi usia minimal pernikahan bukan tanpa alasan ilmiah.\n\n#### 1. Kematangan Organ Reproduksi (Medis)\n- Panggul wanita mencapai ukuran kematangan optimal sekitar usia 20-21 tahun. Hamil di bawah usia 20 tahun meningkatkan risiko robekan jalan lahir, preeklamsia berat, kematian ibu melahirkan (*Maternal Mortality*), dan bayi lahir prematur.\n\n#### 2. Kematangan Otak Prefrontal Cortex (Psikologis)\n- Bagian otak yang mengatur fungsi eksekutif, regulasi emosi impulsif, dan pertimbangan konsekuensi jangka panjang (*prefrontal cortex*) baru matang sepenuhnya di rentang usia 23–25 tahun.\n\n#### 3. Kemandirian Finansial & Pendidikan\n- Memberi waktu remaja menyelesaikan pendidikan tinggi/kejuruan dan memiliki pekerjaan layak sebelum memikul tanggung jawab nafkah keluarga.",
                ]
            );

            // Quiz for Module 2
            $quiz2 = Quiz::firstOrCreate(
                ['module_id' => $mod2->id, 'title' => 'Uji Pemahaman Pendewasaan Usia Perkawinan'],
                [
                    'passing_score' => 70,
                    'attempt_limit' => 3,
                    'status' => 'published',
                ]
            );

            $q2_1 = QuizQuestion::firstOrCreate(
                ['quiz_id' => $quiz2->id, 'sort_order' => 1],
                [
                    'question_text' => 'Berapakah usia pernikahan ideal yang direkomendasikan program GenRe Indonesia?',
                    'question_type' => 'multiple_choice',
                    'explanation' => 'GenRe merekomendasikan minimal 21 tahun untuk perempuan dan 25 tahun untuk laki-laki agar matang secara biologis, emosional, dan ekonomi.',
                ]
            );
            QuizOption::firstOrCreate(['question_id' => $q2_1->id, 'sort_order' => 1], ['option_text' => '17 tahun perempuan & 19 tahun laki-laki', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q2_1->id, 'sort_order' => 2], ['option_text' => 'Minimal 21 tahun perempuan & 25 tahun laki-laki', 'is_correct' => true]);
            QuizOption::firstOrCreate(['question_id' => $q2_1->id, 'sort_order' => 3], ['option_text' => '30 tahun untuk keduanya', 'is_correct' => false]);
            QuizOption::firstOrCreate(['question_id' => $q2_1->id, 'sort_order' => 4], ['option_text' => 'Bebas tanpa batasan usia asalkan saling suka', 'is_correct' => false]);
        }

        // Module 3: Peer Pressure
        if ($catPressure) {
            $mod3 = LearningModule::firstOrCreate(
                ['slug' => 'seni-berkata-tidak-peer-pressure'],
                [
                    'category_id' => $catPressure->id,
                    'author_id' => $authorId,
                    'reviewed_by' => $authorId,
                    'title' => 'Seni Berkata "TIDAK": Menaklukkan Tekanan Negatif Teman Sebaya',
                    'summary' => 'Belajar komunikasi asertif, mempertahankan prinsip diri, dan membangun lingkaran pertemanan yang sehat dan saling mendukung.',
                    'cover_path' => '/images/topic_mentalhealth.jpg',
                    'difficulty' => 'beginner',
                    'estimated_minutes' => 15,
                    'status' => 'published',
                    'published_at' => now()->subDays(2),
                ]
            );

            Lesson::firstOrCreate(
                ['module_id' => $mod3->id, 'sort_order' => 1],
                [
                    'title' => 'Tiga Gaya Komunikasi: Pasif, Agresif, dan Asertif',
                    'content_type' => 'text',
                    'estimated_minutes' => 8,
                    'status' => 'published',
                    'content_body' => "### Bagaimana Kamu Merespons Tekanan?\n\nKetika teman sebaya mengajakmu melakukan hal berisiko (misalnya merokok, tawuran, atau melanggar aturan):\n\n1. **Gaya Pasif:** Mengalah demi diterima, padahal hati nurani menolak. Akibatnya merasa bersalah dan menyesal.\n2. **Gaya Agresif:** Membalas dengan marah, membentak, atau mempermalukan teman. Akibatnya memicu konflik dan permusuhan.\n3. **Gaya Asertif (Jurus GenRe):** Menolak ajakan secara tegas, tenang, sopan, dan jelas tanpa merendahkan orang lain.\n\nContoh Kalimat Asertif:\n> *'Terima kasih sudah mengajak, tapi aku memilih tidak ikut karena punya target latihan sore ini. Yuk kita makan bakso bareng nanti saja.'*",
                ]
            );
        }
    }
}
