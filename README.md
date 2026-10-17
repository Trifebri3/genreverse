# GENREVERSE — Satu Semesta, Sejuta Asa

Platform Digital Advokasi, Edukasi, dan Komunitas Remaja Nasional karya Duta GenRe Indonesia bersama SIYOTA Foundation.

---

## 🚀 Fitur Unggulan

- **GenRe Academy**: Modul edukasi interaktif (Kesehatan Reproduksi, Perencanaan Masa Depan, Gizi & Pencegahan Anemia, Kesehatan Mental Remaja) lengkap dengan Kuis & Evaluasi Pemahaman.
- **LifeQuest**: Gamifikasi eksplorasi karakter remaja untuk menumbuhkan kebiasaan positif dan aksi nyata.
- **Aksara Berdaya & Publikasi Berita**: Ruang advokasi, artikel edukatif, kabar program nasional, dan inspirasi Duta GenRe.
- **Pusat Dokumen & Laporan**: Arsip unduhan resmi juknis, modul fasilitator, template aksi, dan laporan tahunan.
- **Galeri Foto Dokumentasi**: Dokumentasi visual kegiatan jambore, pelatihan konselor sebaya, dan aksi lapangan se-Indonesia.
- **Ucapan, Harapan & Testimoni**: Ruang aspirasi remaja yang dapat dikirimkan langsung oleh publik dan dimoderasi admin.
- **Panel Admin Terpusat**: Manajemen CRUD lengkap untuk Berita, Dokumen, Galeri Foto, dan Testimoni dengan hak akses administrator tunggal.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Vue 3 + TypeScript + Inertia.js
- **Styling**: Tailwind CSS (White-first & Blue-interaction modern aesthetic)
- **Database**: MySQL / SQLite

---

## 💻 Panduan Instalasi Lokal

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/Trifebri3/genreverse.git
   cd genreverse
   ```

2. **Instal Dependensi**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Development Server**:
   ```bash
   npm run dev
   # Pada terminal lain:
   php artisan serve
   ```

Akses aplikasi di peramban pada alamat `http://127.0.0.1:8000`.

---

© 2026 **GENREVERSE** · Duta GenRe Indonesia & SIYOTA Foundation.  
*Dari Remaja, Oleh Remaja, Untuk Remaja.*
