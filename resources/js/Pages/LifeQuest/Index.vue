<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Compass, 
    Sparkles, 
    Gamepad2, 
    CheckCircle2, 
    Play 
} from 'lucide-vue-next';

defineProps<{
    quests: any;
    completedQuestIds: number[];
}>();
</script>

<template>
    <PublicLayout>
        <Head title="LifeQuest Simulation - Digital Playground for GenRe Youth" />

        <!-- Header Hero with Playful Blue Theme & Cosmic Photo -->
        <section class="bg-gradient-to-b from-blue-900 via-blue-800 to-indigo-950 text-white pt-12 pb-16 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    <div class="md:col-span-8 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400 text-slate-900 text-xs font-black uppercase tracking-wider shadow-md">
                            <Gamepad2 class="w-4 h-4" />
                            <span>Fitur Signature GenReverse 🎮</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                            LifeQuest Simulation ✨
                        </h1>

                        <p class="text-blue-100 text-sm sm:text-base leading-relaxed max-w-2xl">
                            Belajar lewat simulasi visual novel dan keputusan bercabang (*branching scenario*). Uji prinsipmu saat menghadapi tekanan teman sebaya, batasan pribadi, dan rancangan masa depan dalam ruang aman tanpa dihakimi!
                        </p>

                        <div class="flex flex-wrap items-center gap-4 pt-2 text-xs text-blue-200">
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-xl border border-white/10">
                                <Sparkles class="w-4 h-4 text-amber-300" /> Dapatkan +75 XP Tiap Quest
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-xl border border-white/10">
                                <Compass class="w-4 h-4 text-amber-300" /> Refleksi Nilai Hidup Positif
                            </span>
                        </div>
                    </div>

                    <!-- Right Visual Card -->
                    <div class="md:col-span-4 hidden md:flex justify-end">
                        <div class="relative rounded-3xl overflow-hidden border-4 border-amber-300 shadow-2xl max-w-xs rotate-1 hover:rotate-0 transition-transform">
                            <img
                                src="/images/lifequest_cover.jpg"
                                alt="Visual Novel LifeQuest"
                                class="w-full h-48 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent flex items-end p-3">
                                <span class="text-[10px] font-black text-amber-300">🌟 Pilih Jalurmu Sendiri!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quests Listing with Photo Cards -->
        <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="quest in quests.data"
                    :key="quest.id"
                    class="bg-white rounded-3xl border-2 border-blue-100 overflow-hidden flex flex-col justify-between hover:shadow-2xl hover:border-blue-300 transition-all duration-300 group"
                >
                    <div>
                        <!-- Quest Visual Cover Photo -->
                        <div class="relative h-48 overflow-hidden bg-indigo-900">
                            <img
                                src="/images/lifequest_cover.jpg"
                                :alt="quest.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-[10px] font-black text-blue-700 shadow-sm">
                                Tingkat: {{ quest.difficulty }}
                            </span>
                            <span class="absolute top-3 right-3 px-3 py-1 bg-amber-400 text-slate-900 rounded-full text-[10px] font-black shadow-sm flex items-center gap-1">
                                <Sparkles class="w-3 h-3" />
                                <span>+{{ quest.xp_reward }} XP</span>
                            </span>
                        </div>

                        <div class="p-6">
                            <h2 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors mb-2">
                                {{ quest.title }}
                            </h2>

                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ quest.description }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500">
                            <CheckCircle2 v-if="completedQuestIds.includes(quest.id)" class="w-4 h-4 text-emerald-600" />
                            <span v-if="completedQuestIds.includes(quest.id)" class="text-emerald-700">Pernah Dituntaskan 🎉</span>
                            <span v-else class="text-blue-600">{{ quest.stages_count }} Adegan Cerita</span>
                        </div>

                        <Link
                            :href="route('lifequest.play', quest.slug)"
                            class="px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 font-black text-xs transition shadow-md flex items-center gap-1.5"
                        >
                            <Play class="w-3.5 h-3.5 fill-slate-900" />
                            <span>{{ completedQuestIds.includes(quest.id) ? 'Main Lagi' : 'Mulai Petualangan' }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
