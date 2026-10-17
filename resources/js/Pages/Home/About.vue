<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Heart, 
    ShieldCheck, 
    Sparkles, 
    Users, 
    CheckCircle2, 
    Award,
    Compass,
    Megaphone,
    MessageSquareQuote,
    BookOpen,
    ArrowRight
} from 'lucide-vue-next';
</script>

<template>
    <PublicLayout>
        <Head title="Tentang GENREVERSE - Satu Semesta, Sejuta Asa | GenRe Indonesia" />

        <div class="py-12 md:py-20 bg-gradient-to-b from-blue-50 via-[#F0F7FF] to-[#F0F7FF]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">
                <!-- Header with Official Core Narrative -->
                <div class="text-center space-y-4 max-w-3xl mx-auto">
                    <AppBadge variant="sunshine">✨ GENREVERSE: Satu Semesta, Sejuta Asa</AppBadge>
                    <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                        Dari Remaja, Oleh Remaja, <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-sky-500 to-amber-500">
                            Untuk Remaja Indonesia. 🌟
                        </span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-normal">
                        GENREVERSE merupakan platform digital advokasi GenRe yang hadir sebagai ruang belajar, berbagi, dan bertumbuh bagi remaja. Menghadirkan kuis interaktif, layanan konseling, kumpulan modul edukasi, artikel inspiratif, serta informasi terkini seputar dunia remaja. GENREVERSE berkomitmen membangun generasi yang resilien, berwawasan, berdaya, dan mampu merencanakan masa depan secara bertanggung jawab.
                    </p>
                </div>

                <!-- Two Core Digital Innovation Pillars: AKSARA BERDAYA & SAPA GenRe -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- AKSARA BERDAYA Card -->
                    <div class="bg-white rounded-3xl border-2 border-blue-200 p-8 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center font-black">
                                <Megaphone class="w-6 h-6" />
                            </div>
                            <div class="space-y-1">
                                <span class="text-[11px] font-black uppercase text-sky-600 tracking-wider">Ruang Advokasi Digital</span>
                                <h2 class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                                    AKSARA BERDAYA
                                </h2>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Merupakan ruang advokasi digital GENREVERSE yang menjadi wadah bagi remaja untuk menyuarakan aspirasi, memperluas wawasan, dan mengembangkan potensi diri. Melalui edukasi, informasi, serta penguatan resiliensi, AKSARA BERDAYA mendorong remaja menjadi generasi yang kritis, tangguh, peduli, dan mampu menciptakan perubahan positif.
                            </p>
                        </div>
                        <div class="pt-6">
                            <Link
                                :href="route('campaigns.index')"
                                class="inline-flex items-center gap-2 text-xs font-black text-sky-600 hover:text-sky-800 transition"
                            >
                                <span>Kunjungi Aksara Berdaya</span>
                                <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </Link>
                        </div>
                    </div>

                    <!-- SAPA GenRe Card -->
                    <div class="bg-white rounded-3xl border-2 border-amber-200 p-8 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-black">
                                <MessageSquareQuote class="w-6 h-6" />
                            </div>
                            <div class="space-y-1">
                                <span class="text-[11px] font-black uppercase text-amber-600 tracking-wider">Sarana Aspirasi & Tanya Duta</span>
                                <h2 class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                                    SAPA GenRe
                                </h2>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Merupakan ruang interaktif dalam platform GENREVERSE yang menjadi wadah bagi remaja untuk menyampaikan pertanyaan, berbagi cerita, dan memperoleh informasi seputar dunia remaja bersama Duta GenRe. Melalui komunikasi yang terbuka, edukatif, dan suportif, SAPA GenRe hadir untuk membantu remaja menemukan pemahaman, menguatkan diri, serta mengambil keputusan yang tepat dalam merencanakan masa depan.
                            </p>
                        </div>
                        <div class="pt-6">
                            <Link
                                :href="route('ask.index')"
                                class="inline-flex items-center gap-2 text-xs font-black text-amber-600 hover:text-amber-800 transition"
                            >
                                <span>Buka SAPA GenRe</span>
                                <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Salam GenRe Banner: Vibrant Royal Blue & Sunshine -->
                <div class="p-8 md:p-10 rounded-3xl bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white shadow-2xl space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-400 text-slate-900 flex items-center justify-center font-black text-2xl shadow-md">
                            👌
                        </div>
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-white">Salam GenRe: Filosofi Tiga Jari</h2>
                            <p class="text-xs sm:text-sm text-blue-200">Ibu jari dan telunjuk membentuk lingkaran (Zero), tiga jari lainnya tegak berdiri teguh</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/15 space-y-2">
                            <div class="text-xs font-black uppercase text-amber-400">Prinsip 01</div>
                            <h3 class="text-base font-bold text-white">Tidak Menikah Dini</h3>
                            <p class="text-xs text-blue-100 leading-relaxed">Menyiapkan kematangan fisik, psikologis, dan finansial: minimal 21 tahun untuk perempuan dan 25 tahun untuk laki-laki.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/15 space-y-2">
                            <div class="text-xs font-black uppercase text-amber-400">Prinsip 02</div>
                            <h3 class="text-base font-bold text-white">Tidak Seks Pranikah</h3>
                            <p class="text-xs text-blue-100 leading-relaxed">Menjaga kehormatan dan integritas diri, melindungi masa depan dari kehamilan tidak diinginkan (KTD) & IMS.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/15 space-y-2">
                            <div class="text-xs font-black uppercase text-amber-400">Prinsip 03</div>
                            <h3 class="text-base font-bold text-white">Tidak Terpapar NAPZA</h3>
                            <p class="text-xs text-blue-100 leading-relaxed">Bebas dari narkotika, psikotropika, zat adiktif, dan minuman keras yang merusak potensi emas generasi muda.</p>
                        </div>
                    </div>
                </div>

                <!-- 8 Fungsi Keluarga Section -->
                <div class="bg-white p-8 md:p-10 rounded-3xl border-2 border-blue-100 space-y-6 shadow-sm">
                    <div class="space-y-1">
                        <AppBadge variant="teal">Ketahanan Bangsa</AppBadge>
                        <h2 class="text-2xl font-black text-slate-900">8 Fungsi Keluarga dalam Pembentukan Karakter Remaja</h2>
                        <p class="text-sm text-slate-600">Keluarga berencana adalah fondasi utama mencetak generasi emas berdaya saing global dan bebas stunting.</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">1. Keagamaan</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">2. Sosial Budaya</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">3. Cinta Kasih</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">4. Perlindungan</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">5. Reproduksi</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">6. Sosialisasi & Edukasi</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">7. Ekonomi</div>
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 font-bold text-sm text-blue-900">8. Pembinaan Lingkungan</div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center p-8 md:p-10 bg-amber-50 rounded-3xl border-2 border-amber-200 space-y-4">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900">Satu Semesta, Sejuta Asa: Mulai Petualanganmu!</h3>
                    <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto">
                        Jadilah bagian dari jutaan remaja Indonesia yang resilien, berwawasan, dan berdaya dalam merencanakan masa depan.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                        <Link :href="route('academy.index')" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition shadow">
                            Mulai Belajar di Akademi
                        </Link>
                        <Link :href="route('lifequest.index')" class="px-6 py-3 rounded-xl bg-amber-400 text-slate-900 font-black text-sm hover:bg-amber-300 transition shadow">
                            Mainkan LifeQuest
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
