<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Sparkles, 
    Award, 
    BookOpen, 
    Compass, 
    Calendar, 
    Megaphone, 
    ArrowRight, 
    CheckCircle2, 
    Clock, 
    Lock,
    UserCheck,
    TrendingUp
} from 'lucide-vue-next';

const props = defineProps<{
    stats: {
        total_xp: number;
        level: number;
        next_level_threshold: number;
        completed_lessons: number;
        badges_count: number;
    };
    recentXp: any[];
    badges: any[];
    recentLessonProgress: any[];
    questAttempts: any[];
    upcomingEvents: any[];
    userCampaigns: any[];
}>();
</script>

<template>
    <PublicLayout>
        <Head title="Dashboard Pribadi - GENREVERSE" />

        <div class="py-10 bg-gradient-to-b from-forest-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- User Profile & Level Card -->
                <div class="bg-gradient-to-br from-forest via-forest-800 to-teal text-white rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sunshine text-forest text-xs font-black uppercase">
                                <Sparkles class="w-3.5 h-3.5" />
                                <span>Level {{ stats.level }} Youth Explorer</span>
                            </div>
                            <h1 class="text-2xl sm:text-4xl font-black text-white">
                                Semangat Belajar, {{ $page.props.auth?.user?.display_name ?? $page.props.auth?.user?.name }}!
                            </h1>
                            <p class="text-xs sm:text-sm text-forest-200">
                                Terus kumpulkan XP melalui modul Akademi, simulasi LifeQuest, dan aksi advokasi sosial.
                            </p>
                        </div>

                        <!-- Level Progress Counter Box -->
                        <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 min-w-[260px] space-y-2">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-forest-200">Total Capaian</span>
                                <span class="text-sunshine font-extrabold text-sm">{{ stats.total_xp }} XP</span>
                            </div>
                            <!-- Bar -->
                            <div class="w-full h-2.5 rounded-full bg-white/20 overflow-hidden">
                                <div
                                    class="h-full bg-sunshine transition-all duration-500 rounded-full"
                                    :style="{ width: `${Math.min(100, (stats.total_xp / stats.next_level_threshold) * 100)}%` }"
                                ></div>
                            </div>
                            <div class="text-[11px] text-forest-200 text-right">
                                Menuju Level {{ stats.level + 1 }}: {{ stats.next_level_threshold - stats.total_xp }} XP lagi
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-forest/10 shadow-sm text-center">
                        <div class="text-2xl font-black text-forest">{{ stats.total_xp }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Total Poin XP</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-forest/10 shadow-sm text-center">
                        <div class="text-2xl font-black text-teal">Level {{ stats.level }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Tingkat Remaja</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-forest/10 shadow-sm text-center">
                        <div class="text-2xl font-black text-sunshine-600">{{ stats.badges_count }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Lencana Diraih</div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-forest/10 shadow-sm text-center">
                        <div class="text-2xl font-black text-emerald-600">{{ stats.completed_lessons }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Materi Tuntas</div>
                    </div>
                </div>

                <!-- Badges Showcase -->
                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <Award class="w-5 h-5 text-sunshine-600" />
                            <h2 class="text-lg font-black text-forest">Koleksi Lencana Penghargaan</h2>
                        </div>
                        <span class="text-xs font-bold text-gray-500">{{ stats.badges_count }} / {{ badges.length }} Terbuka</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        <div
                            v-for="badge in badges"
                            :key="badge.id"
                            class="p-4 rounded-2xl border text-center space-y-2 transition flex flex-col justify-between"
                            :class="[
                                badge.is_earned
                                    ? 'bg-sunshine-50/50 border-sunshine-300 shadow-sm'
                                    : 'bg-gray-50 border-gray-200 opacity-60'
                            ]"
                        >
                            <div>
                                <div
                                    class="w-12 h-12 mx-auto rounded-2xl flex items-center justify-center font-bold text-lg mb-2 shadow-sm"
                                    :class="badge.is_earned ? 'bg-sunshine text-forest' : 'bg-gray-200 text-gray-400'"
                                >
                                    <Sparkles v-if="badge.is_earned" class="w-6 h-6" />
                                    <Lock v-else class="w-5 h-5" />
                                </div>
                                <h3 class="text-xs font-extrabold text-forest line-clamp-1">{{ badge.name }}</h3>
                                <p class="text-[11px] text-gray-500 line-clamp-2 leading-tight mt-1">{{ badge.description }}</p>
                            </div>

                            <div class="pt-2 text-[10px] font-bold" :class="badge.is_earned ? 'text-emerald-700' : 'text-gray-400'">
                                {{ badge.is_earned ? 'Diraih' : 'Terkunci' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Learning Progress & Quests (2 Columns) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left: Recent Lesson Progress -->
                    <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="text-base font-extrabold text-forest flex items-center gap-2">
                                <BookOpen class="w-4 h-4 text-teal" />
                                <span>Aktivitas Belajar Terkini</span>
                            </h3>
                            <Link :href="route('academy.index')" class="text-xs font-bold text-teal hover:underline">
                                Jelajahi Modul &rarr;
                            </Link>
                        </div>

                        <div v-if="recentLessonProgress.length === 0" class="text-center py-8 text-gray-400 text-xs">
                            Belum ada materi yang dipelajari. Mulai dari modul Akademi!
                        </div>

                        <div v-else class="space-y-3">
                            <div
                                v-for="prog in recentLessonProgress"
                                :key="prog.id"
                                class="p-3.5 rounded-2xl bg-forest-50/60 border border-forest-100 flex items-center justify-between gap-3"
                            >
                                <div class="space-y-0.5">
                                    <h4 class="text-xs font-bold text-gray-900 line-clamp-1">{{ prog.lesson?.title }}</h4>
                                    <div class="text-[11px] text-gray-500">{{ prog.lesson?.module?.title }}</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-extrabold shrink-0">
                                    Selesai (+20 XP)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Quest Simulation Activity -->
                    <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="text-base font-extrabold text-forest flex items-center gap-2">
                                <Compass class="w-4 h-4 text-sunshine-600" />
                                <span>Petualangan LifeQuest</span>
                            </h3>
                            <Link :href="route('lifequest.index')" class="text-xs font-bold text-teal hover:underline">
                                Buka Semua Quest &rarr;
                            </Link>
                        </div>

                        <div v-if="questAttempts.length === 0" class="text-center py-8 text-gray-400 text-xs">
                            Belum ada simulasi yang diselesaikan. Coba uji keputusanmu di LifeQuest!
                        </div>

                        <div v-else class="space-y-3">
                            <div
                                v-for="att in questAttempts"
                                :key="att.id"
                                class="p-3.5 rounded-2xl bg-[#F7F8F3] border border-forest/10 flex items-center justify-between gap-3"
                            >
                                <div class="space-y-0.5">
                                    <h4 class="text-xs font-bold text-forest line-clamp-1">{{ att.quest?.title }}</h4>
                                    <div class="text-[11px] text-gray-500">{{ new Date(att.started_at).toLocaleDateString('id-ID') }}</div>
                                </div>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[10px] font-extrabold shrink-0"
                                    :class="att.status === 'completed' ? 'bg-sunshine text-forest' : 'bg-gray-200 text-gray-700'"
                                >
                                    {{ att.status === 'completed' ? 'Tuntas (+75 XP)' : 'Dalam Proses' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ambassador Advocacy Section (If Ambassador) -->
                <div v-if="$page.props.auth?.user?.is_ambassador" class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <Megaphone class="w-5 h-5 text-teal" />
                            <h2 class="text-lg font-black text-forest">Aksi Aksara Berdaya Duta Saya</h2>
                        </div>
                        <Link :href="route('campaigns.create')" class="px-4 py-1.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition">
                            + Gagas Aksi Aksara Berdaya
                        </Link>
                    </div>

                    <div v-if="userCampaigns.length === 0" class="text-center py-6 text-xs text-gray-500">
                        Kamu belum membuat aksi kampanye. Ayo gagas gerakan Aksara Berdaya untuk teman sebaya di wilayahmu!
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            v-for="camp in userCampaigns"
                            :key="camp.id"
                            class="p-4 rounded-2xl bg-forest-50/50 border border-forest-100 flex flex-col justify-between"
                        >
                            <div class="space-y-1">
                                <div class="text-[11px] font-extrabold uppercase text-teal">{{ camp.status }}</div>
                                <h4 class="text-sm font-bold text-forest">{{ camp.title }}</h4>
                            </div>
                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ camp.reports?.length ?? 0 }} Laporan Terverifikasi</span>
                                <Link :href="route('campaigns.show', camp.slug)" class="text-xs font-bold text-forest hover:underline">
                                    Kelola &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
