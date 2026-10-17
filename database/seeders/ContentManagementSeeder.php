<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Document;
use App\Models\GalleryPhoto;
use App\Models\Greeting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        // 1. Articles / Berita
        $articles = [
            [
                'title' => 'Peluncuran Resmi GENREVERSE: Satu Semesta, Sejuta Asa Remaja Indonesia',
                'slug' => 'peluncuran-resmi-genreverse-satu-semesta-sejuta-asa-remaja-indonesia',
                'category' => 'Pengumuman',
                'excerpt' => 'GENREVERSE hadir sebagai ekosistem digital terpadu advokasi, edukasi, dan komunitas remaja se-Indonesia dari BKKBN dan Forum GenRe.',
                'content' => '<p><strong>JAKARTA</strong> — GENREVERSE secara resmi diperkenalkan ke hadapan ribuan remaja dari 38 provinsi di Indonesia. Mengusung tema <em>"Satu Semesta, Sejuta Asa"</em> dan semangat <em>"Dari Remaja, Oleh Remaja, Untuk Remaja"</em>, platform ini dirancang dengan pendekatan modern, inklusif, dan ramah generasi muda.</p><p>Platform ini memadukan tiga pilar utama: <strong>GenRe Academy</strong> untuk pembelajaran modular berjenjang, <strong>LifeQuest</strong> sebagai arena gamifikasi interaktif penunjang kecakapan hidup, dan <strong>Aksara Berdaya</strong> sebagai wadah aksi nyata advokasi serta suara remaja.</p><p>Dengan dukungan penuh BKKBN, GENREVERSE siap mendampingi remaja Indonesia dalam merencanakan masa depan pendidikan, karir, hingga kehidupan berkeluarga secara terencana.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $admin?->id,
                'author_name' => 'Tim Media GenRe Indonesia',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'views_count' => 342,
            ],
            [
                'title' => 'Aksara Berdaya: Ruang Advokasi Digital Remaja Membuka Kolaborasi Lintas Daerah',
                'slug' => 'aksara-berdaya-ruang-advokasi-digital-remaja-kolaborasi',
                'category' => 'Advokasi',
                'excerpt' => 'Remaja dari Sabang sampai Merauke menyuarakan aksi nyata pencegahan perkawinan anak dan edukasi gizi seimbang.',
                'content' => '<p>Ruang advokasi <strong>Aksara Berdaya</strong> menjadi episentrum inisiatif akar rumput para Duta GenRe. Dalam sepekan terakhir, tercatat lebih dari 45 kampanye sosial telah terdaftar dan menjangkau ribuan pelajar SMP-SMA di berbagai wilayah.</p><p>Fokus utama kampanye meliputi kampanye <em>Pencegahan Pernikahan Usia Dini</em>, kampanye gizi <em>Isi Piringku</em> untuk cegah stunting, dan penguatan kesehatan mental bagi pelajar menghadapi ujian sekolah.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $admin?->id,
                'author_name' => 'Divisi Komunikasi & Advokasi',
                'is_published' => true,
                'published_at' => now()->subDays(4),
                'views_count' => 218,
            ],
            [
                'title' => 'Workshop Konselor Sebaya GenRe 2026: Mendengarkan Tanpa Menghakimi',
                'slug' => 'workshop-konselor-sebaya-genre-2026-mendengarkan-tanpa-menghakimi',
                'category' => 'Edukasi',
                'excerpt' => 'Pelatihan intensif 150 konselor sebaya PIK-R mengasah teknik active listening dan psychological first aid sederhana.',
                'content' => '<p>Kesehatan mental dan kenyamanan bercerita merupakan modal penting bagi remaja di era digital. Melalui program <em>SAPA GenRe</em> dan <em>Cerita Kita</em>, para konselor sebaya dibekali keahlian untuk menjadi teman cerita yang aman, solutif, dan mengedepankan etika privasi.</p><p>Pelatihan ini dipandu langsung oleh psikolog anak dan remaja bersama para senior Duta GenRe Indonesia.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $admin?->id,
                'author_name' => 'Humas GenRe Indonesia',
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'views_count' => 512,
            ],
        ];

        foreach ($articles as $art) {
            Article::updateOrCreate(['slug' => $art['slug']], $art);
        }

        // 2. Documents & Reports / Pusat Unduhan
        $documents = [
            [
                'title' => 'Laporan Capaian Tahunan Program GenRe Nasional 2025',
                'slug' => 'laporan-capaian-tahunan-program-genre-nasional-2025',
                'category' => 'Laporan Tahunan',
                'description' => 'Dokumen resmi berisi rekapitulasi capaian advokasi, jumlah PIK-R terjangkau, dan dampak pelatihan Duta GenRe se-Indonesia selama tahun 2025.',
                'file_path' => 'documents/sample_laporan_tahunan_genre_2025.pdf',
                'file_name' => 'Laporan_Tahunan_GenRe_2025.pdf',
                'file_size' => 4520000, // 4.5 MB
                'file_type' => 'pdf',
                'downloads_count' => 128,
                'is_public' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Buku Panduan Fasilitator PIK Remaja (Edisi Revisi IV)',
                'slug' => 'buku-panduan-fasilitator-pik-remaja-edisi-revisi-iv',
                'category' => 'Panduan & Modul',
                'description' => 'Pedoman operasional pembentukan, pengelolaan, dan tata kelola kegiatan Pusat Informasi dan Konseling Remaja (PIK-R).',
                'file_path' => 'documents/sample_panduan_fasilitator_pikr.pdf',
                'file_name' => 'Panduan_Fasilitator_PIKR_2026.pdf',
                'file_size' => 8340000, // 8.3 MB
                'file_type' => 'pdf',
                'downloads_count' => 342,
                'is_public' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Pedoman Teknis Ajang Kreativitas Remaja & Apresiasi Duta GenRe',
                'slug' => 'pedoman-teknis-ajang-kreativitas-remaja-apresiasi-duta-genre',
                'category' => 'Petunjuk Teknis',
                'description' => 'Regulasi penilaian, syarat pendaftaran, dan mekanisme pelaksanaan ADG tingkat provinsi dan nasional.',
                'file_path' => 'documents/sample_juknis_adg_2026.pdf',
                'file_name' => 'Juknis_ADG_GenRe_2026.pdf',
                'file_size' => 2180000, // 2.1 MB
                'file_type' => 'pdf',
                'downloads_count' => 89,
                'is_public' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Template Lembar Rencana Aksi Kampanye Advokasi Remaja',
                'slug' => 'template-lembar-rencana-aksi-kampanye-advokasi-remaja',
                'category' => 'Template Dokumen',
                'description' => 'Lembar kerja Word untuk mempermudah penyusunan proposal kegiatan kampanye sosial di tingkat sekolah atau komunitas.',
                'file_path' => 'documents/sample_template_proposal_kampanye.docx',
                'file_name' => 'Template_Proposal_Kampanye_GenRe.docx',
                'file_size' => 540000, // 540 KB
                'file_type' => 'docx',
                'downloads_count' => 215,
                'is_public' => true,
                'uploaded_by' => $admin?->id,
            ],
        ];

        foreach ($documents as $doc) {
            Document::updateOrCreate(['slug' => $doc['slug']], $doc);
        }

        // 3. Gallery Photos / Galeri Foto
        $photos = [
            [
                'title' => 'Kemeriahan Apresiasi Duta GenRe Indonesia',
                'album_name' => 'Apresiasi Duta GenRe',
                'caption' => 'Para finalis Duta GenRe dari seluruh pelosok tanah air menampilkan kreativitas budaya daerah di panggung kehormatan.',
                'image_path' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-09-12',
                'is_featured' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Edukasi Gizi Seimbang & Pencegahan Anemia di Sekolah',
                'album_name' => 'Aksara Berdaya',
                'caption' => 'Pemberian tablet tambah darah dan sosialisasi Isi Piringku kepada 300 siswi SMP Negeri di Jawa Barat.',
                'image_path' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-09-28',
                'is_featured' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Diskusi Hangat Bersama Konselor Sebaya GenRe',
                'album_name' => 'SAPA GenRe',
                'caption' => 'Sesi curhat interaktif santai membahas manajemen stres dan cara menghadapi quarter-life crisis remaja.',
                'image_path' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-10-04',
                'is_featured' => true,
                'uploaded_by' => $admin?->id,
            ],
            [
                'title' => 'Pelatihan Kepemimpinan Remaja Tangguh & Berencana',
                'album_name' => 'Leadership Camp',
                'caption' => 'Membangun karakter kepemimpinan yang berintegritas dan siap menjadi role model sebaya.',
                'image_path' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'event_date' => '2026-10-10',
                'is_featured' => false,
                'uploaded_by' => $admin?->id,
            ],
        ];

        foreach ($photos as $photo) {
            GalleryPhoto::updateOrCreate(['title' => $photo['title']], $photo);
        }

        // 4. Greetings / Ucapan & Testimoni
        $greetings = [
            [
                'name' => 'Dr. H. Budi Setiawan, M.Si',
                'role_title' => 'Deputi KSPK BKKBN RI',
                'institution' => 'BKKBN Republik Indonesia',
                'message' => 'Selamat atas hadirnya GENREVERSE! Inovasi luar biasa bagi remaja Indonesia agar memiliki ruang digital yang edukatif, sehat, dan menginspirasi untuk merencanakan masa depan.',
                'photo_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'status' => 'approved',
                'is_featured' => true,
                'approved_at' => now(),
            ],
            [
                'name' => 'Nabila Putri Cahyani',
                'role_title' => 'Duta GenRe Indonesia Putri 2025',
                'institution' => 'Forum GenRe Nasional',
                'message' => 'Bangga sekali melihat GENREVERSE lahir! Satu Semesta, Sejuta Asa membuktikan bahwa remaja Indonesia punya suara, potensi, dan daya untuk saling menguatkan.',
                'photo_path' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'status' => 'approved',
                'is_featured' => true,
                'approved_at' => now(),
            ],
            [
                'name' => 'Fajar Alamsyah',
                'role_title' => 'Ketua Forum GenRe Jawa Timur',
                'institution' => 'Forum GenRe Jatim',
                'message' => 'Aplikasi ini sangat ramah remaja, tampilan segar dan interaktif! Sangat membantu kami menyosialisasikan program Triad KRR ke sekolah-sekolah.',
                'photo_path' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                'status' => 'approved',
                'is_featured' => true,
                'approved_at' => now(),
            ],
            [
                'name' => 'Siti Aisyah Rahma',
                'role_title' => 'Konselor Sebaya PIK-R SMAN 1 Denpasar',
                'institution' => 'PIK-R Ceria Denpasar',
                'message' => 'Fitur SAPA GenRe dan materi edukasinya lengkap banget. Teman-teman di sekolah jadi lebih terbuka dan tidak malu lagi untuk berkonsultasi.',
                'photo_path' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80',
                'status' => 'approved',
                'is_featured' => false,
                'approved_at' => now(),
            ],
        ];

        foreach ($greetings as $greet) {
            Greeting::updateOrCreate(['name' => $greet['name']], $greet);
        }
    }
}
