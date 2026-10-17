<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search,
    ArrowRight, 
    Star, 
    BookOpen, 
    Users, 
    Award, 
    Smile, 
    Compass, 
    Megaphone, 
    Calendar, 
    MapPin, 
    Heart, 
    CheckCircle2, 
    ChevronRight, 
    Gamepad2, 
    Sparkles, 
    ShieldCheck,
    MessageSquareQuote,
    HeartHandshake,
    Newspaper,
    ImagePlus,
    FolderArchive,
    UploadCloud,
    X,
    Eye
} from 'lucide-vue-next';

const props = defineProps<{
    featuredModules: any[];
    featuredQuest: any;
    activeCampaigns: any[];
    upcomingEvents: any[];
    featuredStories: any[];
    latestArticles?: any[];
    galleryPhotos?: any[];
    greetings?: any[];
    metrics: {
        total_youth: number;
        modules_count: number;
        provinces_count: number;
        verified_actions: number;
    };
}>();

const searchQuery = ref('');
const showGreetingModal = ref(false);
const greetingPhotoPreview = ref<string | null>(null);

const greetingForm = ref({
    name: '',
    role_title: 'Remaja GenRe',
    institution: '',
    message: '',
    photo: null as File | null,
});

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get(route('academy.index'), { search: searchQuery.value.trim() });
    } else {
        router.get(route('academy.index'));
    }
};

const handleGreetingFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        greetingForm.value.photo = target.files[0];
        greetingPhotoPreview.value = URL.createObjectURL(target.files[0]);
    }
};

const submitGreeting = () => {
    const formData = new FormData();
    formData.append('name', greetingForm.value.name);
    formData.append('role_title', greetingForm.value.role_title);
    formData.append('institution', greetingForm.value.institution);
    formData.append('message', greetingForm.value.message);
    if (greetingForm.value.photo) {
        formData.append('photo', greetingForm.value.photo);
    }

    router.post(route('greetings.store'), formData, {
        onSuccess: () => {
            showGreetingModal.value = false;
            greetingForm.value = {
                name: '',
                role_title: 'Remaja GenRe',
                institution: '',
                message: '',
                photo: null,
            };
            greetingPhotoPreview.value = null;
        },
    });
};
</script>

<template>
    <PublicLayout>
        <Head title="GENREVERSE - Satu Semesta, Sejuta Asa | Dari Remaja, Oleh Remaja, Untuk Remaja" />

        <!-- 1. HERO SECTION (INSPIRED BY DREAMS LMS REFERENCE) -->
        <section class="relative overflow-hidden bg-gradient-to-br from-rose-50/40 via-white to-sky-50/50 pt-10 pb-20 md:pt-16 md:pb-28">
            <!-- Subtle Ambient Background Glows -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-rose-200/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-24 w-96 h-96 bg-sky-200/30 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    <!-- Left: Compelling Content & Search -->
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <!-- Subtitle Tagline -->
                        <div class="inline-flex items-center gap-2">
                            <span class="text-xs sm:text-sm font-bold text-rose-500 uppercase tracking-wider">
                                Ruang Tumbuh & Advokasi Remaja
                            </span>
                        </div>

                        <!-- Big Bold Inspiring Headline -->
                        <h1 class="text-4xl sm:text-5xl lg:text-[56px] font-black text-[#101D35] tracking-tight leading-[1.15]">
                            Satu Semesta, <br />
                            <span class="text-[#2166F3]">Sejuta Asa Remaja.</span> <br />
                            Belajar, Berbagi & Bertumbuh.
                        </h1>

                        <!-- Platform Core Narrative -->
                        <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-normal max-w-xl mx-auto lg:mx-0">
                            GENREVERSE merupakan platform digital advokasi GenRe yang hadir sebagai ruang belajar, berbagi, dan bertumbuh bagi remaja. Menghadirkan kuis interaktif, layanan konseling <strong class="text-[#2166F3] font-bold">SAPA GenRe</strong>, modul edukasi, simulasi <strong class="text-[#2166F3] font-bold">LifeQuest</strong>, serta aksi nyata <strong class="text-[#2166F3] font-bold">Aksara Berdaya</strong>.
                        </p>

                        <!-- Search Bar (Exact Reference Match) -->
                        <form @submit.prevent="handleSearch" class="pt-2 max-w-xl mx-auto lg:mx-0">
                            <div class="relative bg-white rounded-full shadow-lg shadow-slate-200/50 border border-slate-200/80 p-1.5 pl-6 flex items-center gap-3 transition-shadow focus-within:shadow-xl focus-within:border-blue-400">
                                <Search class="w-5 h-5 text-slate-400 shrink-0" />
                                <input 
                                    v-model="searchQuery"
                                    type="text" 
                                    placeholder="Cari modul kesehatan reproduksi, kuis, atau LifeQuest..." 
                                    class="w-full bg-transparent border-none text-sm text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none"
                                />
                                <button 
                                    type="submit"
                                    class="w-11 h-11 rounded-full bg-[#FF5364] hover:bg-rose-600 text-white flex items-center justify-center shrink-0 transition-transform duration-200 hover:scale-105 shadow-md shadow-rose-500/25"
                                    title="Cari"
                                >
                                    <ArrowRight class="w-5 h-5" />
                                </button>
                            </div>
                        </form>

                        <!-- Social Proof & Star Rating Strip -->
                        <div class="pt-3 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 text-center sm:text-left">
                            <div class="space-y-0.5">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Dipercaya oleh Duta GenRe & Remaja Se-Indonesia
                                </p>
                                <div class="flex items-center justify-center sm:justify-start gap-4">
                                    <span class="text-2xl sm:text-3xl font-black text-[#101D35]">{{ metrics.total_youth.toLocaleString('id-ID') }}+</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-lg font-bold text-[#101D35]">4.9</span>
                                        <div class="flex text-amber-400">
                                            <Star class="w-4 h-4 fill-amber-400" />
                                            <Star class="w-4 h-4 fill-amber-400" />
                                            <Star class="w-4 h-4 fill-amber-400" />
                                            <Star class="w-4 h-4 fill-amber-400" />
                                            <Star class="w-4 h-4 fill-amber-400" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Modern Youth Visual with Playful Shapes (Reference Match) -->
                    <div class="lg:col-span-5 relative flex justify-center">
                        <div class="relative w-full max-w-md lg:max-w-none">
                            <!-- Playful Soft Coral Angled Backdrop Shape -->
                            <div class="absolute -inset-4 bg-gradient-to-tr from-rose-400 to-rose-300 rounded-[38px] rotate-3 opacity-90 shadow-xl"></div>
                            
                            <!-- Floating Abstract 3D Geometric Accents -->
                            <div class="absolute -top-6 -left-6 w-12 h-12 rounded-xl bg-amber-400 rotate-12 shadow-lg z-20 flex items-center justify-center">
                                <Sparkles class="w-6 h-6 text-white" />
                            </div>
                            <div class="absolute -bottom-4 -right-4 w-14 h-14 rounded-full bg-sky-400 shadow-lg z-20 flex items-center justify-center">
                                <Award class="w-7 h-7 text-white" />
                            </div>

                            <!-- Main Photo Frame -->
                            <div class="relative rounded-3xl overflow-hidden bg-white shadow-2xl z-10 border-4 border-white">
                                <img
                                    src="/images/hero_youth.jpg"
                                    alt="GenRe Youth Squad"
                                    class="w-full h-[380px] sm:h-[440px] object-cover"
                                />

                                <!-- Photo Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>

                                <!-- Photo Bottom Tag -->
                                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white">
                                    <div class="flex items-center gap-2.5">
                                        <img 
                                            src="/images/logogenre.png" 
                                            alt="GenRe Logo" 
                                            class="w-9 h-9 object-contain drop-shadow-md shrink-0" 
                                        />
                                        <div>
                                            <div class="text-xs font-black">GenRe Youth Squad</div>
                                            <div class="text-[10px] text-rose-200">Keren, Cerdas, Berencana!</div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] font-black border border-white/30">
                                        Edisi 2026
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. STATS COUNTER STRIP (THE 4 FLOATING CARDS - EXACT REFERENCE MATCH) -->
                <div class="mt-16 sm:mt-20 max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Card 1: Modul -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                            <BookOpen class="w-6 h-6" />
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-[#101D35]">{{ metrics.modules_count }}</div>
                            <div class="text-xs font-semibold text-slate-500">Modul Terakreditasi</div>
                        </div>
                    </div>

                    <!-- Card 2: Duta GenRe -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <Users class="w-6 h-6" />
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-[#101D35]">150+</div>
                            <div class="text-xs font-semibold text-slate-500">Duta GenRe Sebaya</div>
                        </div>
                    </div>

                    <!-- Card 3: Provinsi -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <MapPin class="w-6 h-6" />
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-[#101D35]">{{ metrics.provinces_count }}</div>
                            <div class="text-xs font-semibold text-slate-500">Provinsi Terjangkau</div>
                        </div>
                    </div>

                    <!-- Card 4: Dampak Terverifikasi -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                            <CheckCircle2 class="w-6 h-6" />
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-black text-[#101D35]">{{ metrics.verified_actions.toLocaleString('id-ID') }}+</div>
                            <div class="text-xs font-semibold text-slate-500">Dampak Terverifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. TOP CATEGORY SECTION (EXACT REFERENCE MATCH) -->
        <section class="py-16 md:py-24 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Program Unggulan</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-[#101D35] tracking-tight">Pilar Utama Remaja Berdaya</h2>
                        <p class="text-slate-500 text-sm max-w-xl">
                            Ruang terpadu untuk belajar santai, simulasi keputusan hidup, advokasi, dan konseling sebaya.
                        </p>
                    </div>
                    <Link
                        :href="route('academy.index')"
                        class="self-start sm:self-auto px-5 py-2.5 rounded-full border border-slate-300 hover:border-[#2166F3] text-slate-700 hover:text-[#2166F3] font-bold text-xs transition"
                    >
                        Lihat Semua Program
                    </Link>
                </div>

                <!-- 4 Clean Category Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Pillar 1: GenRe Academy -->
                    <Link
                        :href="route('academy.index')"
                        class="p-8 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center group"
                    >
                        <div class="w-16 h-16 rounded-full bg-blue-50 text-[#2166F3] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <BookOpen class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-black text-[#101D35] group-hover:text-[#2166F3] transition-colors mb-1">
                            GenRe Academy
                        </h3>
                        <p class="text-xs text-slate-500 mb-2">Microlearning & Kuis XP</p>
                        <span class="text-[11px] font-bold text-[#2166F3]">{{ metrics.modules_count }} Modul Aktif</span>
                    </Link>

                    <!-- Pillar 2: LifeQuest -->
                    <Link
                        :href="route('lifequest.index')"
                        class="p-8 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center group"
                    >
                        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <Compass class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-black text-[#101D35] group-hover:text-[#2166F3] transition-colors mb-1">
                            LifeQuest
                        </h3>
                        <p class="text-xs text-slate-500 mb-2">Simulasi Pilihan Hidup</p>
                        <span class="text-[11px] font-bold text-amber-600">Skenario Visual Bercabang</span>
                    </Link>

                    <!-- Pillar 3: Aksara Berdaya -->
                    <Link
                        :href="route('campaigns.index')"
                        class="p-8 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center group"
                    >
                        <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <Megaphone class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-black text-[#101D35] group-hover:text-[#2166F3] transition-colors mb-1">
                            Aksara Berdaya
                        </h3>
                        <p class="text-xs text-slate-500 mb-2">Ruang Aksi & Advokasi</p>
                        <span class="text-[11px] font-bold text-rose-600">Aspirasi Positif Remaja</span>
                    </Link>

                    <!-- Pillar 4: SAPA GenRe -->
                    <Link
                        :href="route('ask.index')"
                        class="p-8 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center group"
                    >
                        <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <MessageSquareQuote class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-black text-[#101D35] group-hover:text-[#2166F3] transition-colors mb-1">
                            SAPA GenRe
                        </h3>
                        <p class="text-xs text-slate-500 mb-2">Konseling Sebaya Online</p>
                        <span class="text-[11px] font-bold text-teal-600">Privat, Aman & Bebas Tabu</span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- 4. FEATURED COURSES SECTION (EXACT REFERENCE MATCH) -->
        <section class="py-16 md:py-24 bg-gradient-to-b from-white via-sky-50/30 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Kurikulum Pilihan</span>
                        <h2 class="text-3xl sm:text-4xl font-black text-[#101D35] tracking-tight">Materi Terverifikasi BKKBN</h2>
                        <p class="text-slate-500 text-sm max-w-xl">
                            Edukasi ilmiah ramah remaja: santai, tidak menghakimi, dan bebas tabu.
                        </p>
                    </div>
                    <Link
                        :href="route('academy.index')"
                        class="self-start sm:self-auto px-5 py-2.5 rounded-full border border-slate-300 hover:border-[#2166F3] text-slate-700 hover:text-[#2166F3] font-bold text-xs transition"
                    >
                        Lihat Semua Modul ({{ metrics.modules_count }})
                    </Link>
                </div>

                <!-- Course Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="(mod, idx) in featuredModules"
                        :key="mod.id"
                        class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between group"
                    >
                        <div>
                            <!-- Cover Photo Header -->
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                <img
                                    :src="idx === 0 ? '/images/topic_kespro.jpg' : (idx === 1 ? '/images/academy_cover.jpg' : '/images/topic_mentalhealth.jpg')"
                                    :alt="mod.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <span class="absolute bottom-3 right-3 px-3 py-1 bg-emerald-500 text-white rounded-full text-[11px] font-black shadow-sm">
                                    GRATIS
                                </span>
                            </div>

                            <div class="p-6 space-y-3">
                                <!-- Author / Duta GenRe info -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-[#2166F3] flex items-center justify-center text-[10px] font-black">
                                            GR
                                        </div>
                                        <span class="text-xs font-semibold text-slate-600">Duta GenRe Indonesia</span>
                                    </div>
                                    <button class="text-slate-400 hover:text-rose-500 transition" title="Simpan Modul">
                                        <Heart class="w-4 h-4" />
                                    </button>
                                </div>

                                <!-- Title -->
                                <h3 class="text-base font-black text-[#101D35] group-hover:text-[#2166F3] transition-colors line-clamp-2 leading-snug">
                                    {{ mod.title }}
                                </h3>

                                <!-- Summary -->
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ mod.summary }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span class="font-semibold">⏱️ {{ mod.estimated_minutes }} Menit Belajar</span>
                            <Link
                                :href="route('academy.show', mod.slug)"
                                class="font-bold text-[#2166F3] hover:underline flex items-center gap-1"
                            >
                                <span>Buka Modul</span>
                                <ChevronRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. SIGNATURE LIFEQUEST SPOTLIGHT (SIMULASI INTERAKTIF) -->
        <section v-if="featuredQuest" class="py-16 md:py-24 bg-[#101D35] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-6 space-y-5">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FFC21A] text-[#101D35] text-xs font-black">
                            <Gamepad2 class="w-4 h-4" />
                            <span>Simulasi Pilihan Bulan Ini</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                            {{ featuredQuest.title }}
                        </h2>
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                            {{ featuredQuest.description }}
                        </p>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300 pt-2">
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full border border-white/10">
                                <CheckCircle2 class="w-4 h-4 text-[#FFC21A]" /> Skenario Keputusan Bercabang
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full border border-white/10">
                                <Award class="w-4 h-4 text-[#FFC21A]" /> +{{ featuredQuest.xp_reward }} XP Reward
                            </span>
                        </div>

                        <div class="pt-4">
                            <Link
                                :href="route('lifequest.play', featuredQuest.slug)"
                                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-[#FFC21A] hover:bg-amber-300 text-[#101D35] font-black text-sm transition shadow-lg shadow-amber-400/20"
                            >
                                <Gamepad2 class="w-5 h-5" />
                                <span>Mainkan Simulasi (+75 XP)</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>

                    <!-- Visual Novel Preview Image Card -->
                    <div class="lg:col-span-6 flex justify-center">
                        <div class="relative w-full max-w-lg rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl">
                            <img
                                src="/images/lifequest_cover.jpg"
                                alt="Skenario LifeQuest"
                                class="w-full h-80 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#101D35]/90 via-transparent to-transparent flex flex-col justify-end p-6">
                                <span class="text-xs text-[#FFC21A] font-bold uppercase tracking-wider mb-1">Keputusan Bijak Remaja</span>
                                <h4 class="text-lg font-black text-white">"Setiap pilihan membentuk masa depanmu."</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. SAPA GENRE & AGENDA KEGIATAN -->
        <section class="py-16 md:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <!-- Left: SAPA GenRe with Counselor Photo -->
                    <div class="space-y-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Layanan Aspirasi</span>
                        <h2 class="text-3xl font-black text-[#101D35] tracking-tight">
                            SAPA GenRe: Sampaikan Aspirasi, Temukan Jawaban
                        </h2>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            <strong class="text-[#2166F3]">SAPA GenRe</strong> merupakan ruang interaktif dalam platform GENREVERSE yang menjadi wadah bagi remaja untuk menyampaikan pertanyaan, berbagi cerita, dan memperoleh informasi seputar dunia remaja bersama Duta GenRe melalui komunikasi terbuka, edukatif, dan suportif.
                        </p>

                        <!-- Counselor Card with Photo -->
                        <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-100 flex items-center gap-4">
                            <img
                                src="/images/peer_counselor.jpg"
                                alt="Konselor Sebaya SAPA GenRe"
                                class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-sm shrink-0"
                            />
                            <div class="space-y-0.5">
                                <div class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">Konselor Sebaya SAPA GenRe</div>
                                <div class="text-xs font-black text-[#101D35]">Kak Sari & Rekan Duta GenRe</div>
                                <p class="text-[11px] text-slate-600 italic">"Membantu remaja menemukan pemahaman, menguatkan diri, serta mengambil keputusan yang tepat dalam merencanakan masa depan."</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <Link
                                :href="route('ask.index')"
                                class="px-7 py-3 rounded-full bg-[#2166F3] hover:bg-blue-700 text-white font-bold text-sm transition shadow-sm"
                            >
                                Buka SAPA GenRe
                            </Link>
                            <Link
                                :href="route('stories.index')"
                                class="px-7 py-3 rounded-full border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition"
                            >
                                Baca Cerita Kita
                            </Link>
                        </div>
                    </div>

                    <!-- Right: Upcoming Events -->
                    <div class="p-6 md:p-8 rounded-3xl bg-slate-50 border border-slate-100 space-y-6">
                        <!-- Jamboree Event Banner Photo -->
                        <div class="relative h-44 rounded-2xl overflow-hidden shadow-sm">
                            <img
                                src="/images/community_cover.jpg"
                                alt="GenRe Jamboree"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#101D35]/80 via-transparent to-transparent flex flex-col justify-end p-4">
                                <span class="text-[10px] font-black uppercase text-[#FFC21A]">Temu & Kolaborasi Remaja</span>
                                <h4 class="text-sm font-black text-white">Jambore & Temu Nasional Duta GenRe</h4>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-black text-[#101D35] flex items-center gap-2">
                                <Calendar class="w-5 h-5 text-[#2166F3]" />
                                <span>Kegiatan Terdekat</span>
                            </h3>
                            <Link :href="route('events.index')" class="text-xs font-bold text-[#2166F3] hover:underline">
                                Semua Acara &rarr;
                            </Link>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="evt in upcomingEvents"
                                :key="evt.id"
                                class="p-4 rounded-xl bg-white border border-slate-100 shadow-xs flex items-start justify-between gap-4"
                            >
                                <div class="space-y-1">
                                    <div class="text-[11px] font-bold text-[#2166F3]">
                                        {{ new Date(evt.starts_at).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short' }) }}
                                    </div>
                                    <h4 class="text-xs font-bold text-[#101D35] line-clamp-1">{{ evt.title }}</h4>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1">
                                        <MapPin class="w-3.5 h-3.5 text-slate-400" />
                                        <span class="truncate">{{ evt.location_label }}</span>
                                    </div>
                                </div>
                                <Link
                                    :href="route('events.show', evt.id)"
                                    class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-[#2166F3] hover:bg-[#2166F3] hover:text-white transition"
                                >
                                    Daftar (+15 XP)
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. WARTA & BERITA GENRE TERKINI -->
        <section class="py-16 md:py-24 bg-[#F8FAFC] border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#2166F3]">Kabar & Liputan Terkini</span>
                        <h2 class="text-3xl font-black text-[#101D35] tracking-tight">
                            Warta GenRe: Kabar & Inspirasi Pemuda
                        </h2>
                        <p class="text-sm text-slate-600 max-w-xl">
                            Informasi terkini seputar aksi nyata advokasi, capaian Duta GenRe, dan edukasi kesehatan reproduksi remaja.
                        </p>
                    </div>
                    <Link
                        :href="route('articles.index')"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#2166F3] hover:gap-3 transition self-start sm:self-auto"
                    >
                        <span>Lihat Semua Berita</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="art in latestArticles"
                        :key="art.id"
                        class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg transition duration-300 flex flex-col justify-between group"
                    >
                        <div class="h-48 bg-slate-100 overflow-hidden relative">
                            <img
                                v-if="art.cover_image"
                                :src="art.cover_image"
                                :alt="art.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            />
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-white/95 text-[#2166F3] backdrop-blur-sm shadow-xs">
                                    {{ art.category }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h3 class="font-bold text-base text-[#101D35] group-hover:text-[#2166F3] transition line-clamp-2 leading-snug">
                                    <Link :href="route('articles.show', art.slug)">
                                        {{ art.title }}
                                    </Link>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ art.excerpt }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>{{ art.author_name || 'Humas GenRe' }}</span>
                                <span class="flex items-center gap-1">
                                    <Eye class="w-3.5 h-3.5" />
                                    <span>{{ art.views_count }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. GALERI DOKUMENTASI KEGIATAN & PUSAT DOKUMEN -->
        <section class="py-16 md:py-24 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#FFC21A]">Dokumentasi Semesta</span>
                        <h2 class="text-3xl font-black text-[#101D35] tracking-tight">
                            Galeri Aksi Nyata & Pusat Unduhan Dokumen
                        </h2>
                        <p class="text-sm text-slate-600 max-w-xl">
                            Rekaman visual kegiatan pemuda dan akses arsip laporan, modul, serta petunjuk teknis resmi Duta GenRe.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <Link
                            :href="route('documents.index')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
                        >
                            <FolderArchive class="w-4 h-4 text-teal" />
                            <span>Pusat Unduhan Laporan</span>
                        </Link>
                        <Link
                            :href="route('gallery.index')"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-[#2166F3] text-white text-xs font-bold hover:bg-blue-700 shadow-sm transition"
                        >
                            <ImagePlus class="w-4 h-4" />
                            <span>Buka Galeri Foto</span>
                        </Link>
                    </div>
                </div>

                <!-- 4 Photo Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="photo in galleryPhotos"
                        :key="photo.id"
                        class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-xs hover:shadow-md transition group flex flex-col justify-between"
                    >
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img
                                :src="photo.image_path"
                                :alt="photo.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            />
                            <div class="absolute top-2.5 left-2.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-black/60 text-white backdrop-blur-md">
                                    {{ photo.album_name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 space-y-1">
                            <h4 class="font-bold text-xs text-[#101D35] line-clamp-1">{{ photo.title }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-1">{{ photo.caption || 'Dokumentasi kegiatan resmi.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. POJOK UCAPAN & HARAPAN REMAJA ("INPUT UCAPAN DAN FOTO") -->
        <section class="py-16 md:py-24 bg-gradient-to-b from-slate-50 to-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Pojok Aspirasi & Suara Remaja</span>
                        <h2 class="text-3xl font-black text-[#101D35] tracking-tight">
                            Ucapan & Harapan untuk Remaja Indonesia
                        </h2>
                        <p class="text-sm text-slate-600 max-w-xl">
                            Dengarkan pesan hangat dari sesama remaja, konselor sebaya, dan Duta GenRe. Kamu juga bisa menitipkan harapanmu di sini!
                        </p>
                    </div>

                    <!-- Trigger Button for Input Ucapan & Foto -->
                    <button
                        @click="showGreetingModal = true"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-rose-500 text-white font-bold text-sm hover:bg-rose-600 shadow-md transition self-start sm:self-auto"
                    >
                        <HeartHandshake class="w-4 h-4" />
                        <span>Kirim Ucapan & Foto Anda</span>
                    </button>
                </div>

                <!-- Testimonial Greetings Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="greet in greetings"
                        :key="greet.id"
                        class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs hover:shadow-lg transition flex flex-col justify-between space-y-4"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-rose-50 border-2 border-rose-100 shrink-0">
                                <img
                                    v-if="greet.photo_path"
                                    :src="greet.photo_path"
                                    :alt="greet.name"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-rose-500 font-bold text-sm">
                                    {{ greet.name.charAt(0) }}
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-xs text-[#101D35] truncate">{{ greet.name }}</h4>
                                <div class="text-[10px] font-bold text-rose-600 truncate">{{ greet.role_title }}</div>
                                <div v-if="greet.institution" class="text-[10px] text-slate-400 truncate">{{ greet.institution }}</div>
                            </div>
                        </div>

                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100 text-xs text-slate-700 leading-relaxed italic relative">
                            "{{ greet.message }}"
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                            <span class="flex items-center gap-1 text-amber-500 font-bold">
                                <Star class="w-3 h-3 fill-amber-500" />
                                <span>Suara GenRe</span>
                            </span>
                            <span>{{ new Date(greet.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- INPUT UCAPAN & FOTO MODAL -->
        <div v-if="showGreetingModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-xs">
            <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-scale-in">
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-[#101D35] flex items-center gap-2">
                            <HeartHandshake class="w-5 h-5 text-rose-500" />
                            <span>Kirim Ucapan & Pasang Fotomu</span>
                        </h3>
                        <p class="text-xs text-slate-500">Berikan semangat dan pesan inspirasi untuk jutaan remaja Indonesia.</p>
                    </div>
                    <button @click="showGreetingModal = false" class="p-1.5 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Modal Form -->
                <form @submit.prevent="submitGreeting" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input
                            v-model="greetingForm.name"
                            type="text"
                            required
                            placeholder="Contoh: Rian Anggara"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3]"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Peran / Jabatan *</label>
                            <input
                                v-model="greetingForm.role_title"
                                type="text"
                                required
                                placeholder="Duta GenRe / Konselor / Siswa"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3]"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Asal Lembaga / Daerah</label>
                            <input
                                v-model="greetingForm.institution"
                                type="text"
                                placeholder="Forum GenRe Jabar / SMAN 1"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3]"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pesan Ucapan / Harapan *</label>
                        <textarea
                            v-model="greetingForm.message"
                            rows="3"
                            required
                            placeholder="Tuliskan ucapan selamat, harapan, atau motivasi untuk remaja se-Indonesia..."
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-slate-200 focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3]"
                        ></textarea>
                    </div>

                    <!-- Upload Foto Pribadi / Profil -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Anda (Opsional)</label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-dashed border-slate-300 hover:bg-slate-100 text-xs font-semibold text-slate-600 transition">
                                <UploadCloud class="w-4 h-4 text-rose-500" />
                                <span>Pilih Foto</span>
                                <input type="file" accept="image/*" @change="handleGreetingFileChange" class="hidden" />
                            </label>
                            <span class="text-[11px] text-slate-400">JPG, PNG maks 5MB</span>
                        </div>
                        <div v-if="greetingPhotoPreview" class="mt-3 relative w-20 h-20 rounded-full overflow-hidden border-2 border-rose-200 shadow-sm">
                            <img :src="greetingPhotoPreview" alt="Preview Foto" class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="showGreetingModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-rose-500 text-white hover:bg-rose-600 shadow-sm transition"
                        >
                            Kirimkan Ucapan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </PublicLayout>
</template>
