<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { ShieldCheck, Lock, EyeOff, FileText, CheckCircle2 } from 'lucide-vue-next';
</script>

<template>
    <PublicLayout>
        <Head title="Kebijakan Privasi & Perlindungan Remaja - GENREVERSE" />

        <div class="py-12 md:py-20 bg-gradient-to-b from-forest-50 to-[#F7F8F3]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="space-y-3">
                    <AppBadge variant="teal">Safe by Design</AppBadge>
                    <h1 class="text-3xl sm:text-4xl font-black text-forest">
                        Kebijakan Privasi & Perlindungan Remaja
                    </h1>
                    <p class="text-sm text-gray-600">
                        Terakhir diperbarui: 16 Oktober 2026. GENREVERSE tunduk pada Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27/2022) Republik Indonesia.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-forest/10 space-y-8 shadow-sm text-sm text-gray-700 leading-relaxed">
                    <section class="space-y-3">
                        <h2 class="text-lg font-bold text-forest flex items-center gap-2">
                            <Lock class="w-5 h-5 text-teal" />
                            <span>1. Prinsip Minimalisasi Pengumpulan Data (Data Minimization)</span>
                        </h2>
                        <p>
                            GENREVERSE dirancang dengan prinsip bahwa ruang belajar remaja harus bebas dari eksploitasi data komersial. Kami <strong>TIDAK PERNAH</strong> meminta atau menyimpan nomor KTP/NIK lengkap, alamat rumah tempat tinggal, lokasi GPS real-time, atau riwayat pesan obrolan privat antar pengguna.
                        </p>
                        <ul class="list-disc pl-5 space-y-1 text-gray-600">
                            <li>Data akun hanya mencakup nama pengguna/nama panggilan dan surel (email) untuk penyimpanan progres.</li>
                            <li>Tahun lahir dikumpulkan secara opsional semata untuk menyesuaikan tingkat kesulitan materi edukasi.</li>
                        </ul>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-lg font-bold text-forest flex items-center gap-2">
                            <EyeOff class="w-5 h-5 text-teal" />
                            <span>2. Kerahasiaan Sesi Konsultasi & SAPA GenRe</span>
                        </h2>
                        <p>
                            Fitur <em>SAPA GenRe</em> (Sarana Aspirasi dan Pertanyaan Remaja) memungkinkan remaja mengirimkan pertanyaan sensitif seputar kesehatan reproduksi, relasi, dan perencanaan masa depan secara <strong>100% Anonim</strong>. Identitas penanya tidak akan ditampilkan kepada publik, dan hanya diakses oleh tim kurator/moderator resmi guna memastikan keamanan serta rujukan ke tenaga medis/konseling jika terindikasi situasi krisis.
                        </p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-lg font-bold text-forest flex items-center gap-2">
                            <ShieldCheck class="w-5 h-5 text-teal" />
                            <span>3. Keamanan Informasi & Enkripsi Kata Sandi</span>
                        </h2>
                        <p>
                            Seluruh kata sandi dienkripsi menggunakan algoritma hashing modern (Bcrypt). Sesi pengguna dilindungi token CSRF dan pembatasan frekuensi percobaan (Rate Limiting) untuk menangkal serangan otomatis dan brute-force.
                        </p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-lg font-bold text-forest flex items-center gap-2">
                            <FileText class="w-5 h-5 text-teal" />
                            <span>4. Hak Pengguna atas Koreksi dan Penghapusan Data</span>
                        </h2>
                        <p>
                            Setiap pengguna berhak memperbarui preferensi akun, menyunting data profil, atau meminta penghapusan akun beserta seluruh riwayat capaian melalui menu pengaturan profil atau menghubungi Pusat Dukungan GenRe.
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
