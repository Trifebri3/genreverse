<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Compass, 
    Sparkles, 
    ArrowLeft, 
    Play, 
    CheckCircle2, 
    Award, 
    RotateCcw 
} from 'lucide-vue-next';

defineProps<{
    quest: any;
    lastAttempt?: any;
}>();
</script>

<template>
    <PublicLayout>
        <Head :title="`${quest.title} - LifeQuest`" />

        <div class="py-12 bg-gradient-to-b from-forest-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Back -->
                <Link :href="route('lifequest.index')" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-forest transition">
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke Daftar LifeQuest</span>
                </Link>

                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-sm space-y-6">
                    <div class="flex items-center justify-between gap-3">
                        <AppBadge variant="forest">Tingkat Kesulitan: {{ quest.difficulty }}</AppBadge>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sunshine-50 text-forest text-xs font-bold border border-sunshine-300">
                            <Sparkles class="w-4 h-4 text-sunshine-600" />
                            <span>Hadiah: +{{ quest.xp_reward }} XP</span>
                        </span>
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-2xl sm:text-4xl font-black text-forest tracking-tight">
                            {{ quest.title }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                            {{ quest.description }}
                        </p>
                    </div>

                    <!-- Attempt History Banner if completed -->
                    <div v-if="lastAttempt && lastAttempt.status === 'completed'" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <CheckCircle2 class="w-5 h-5 text-emerald-600 shrink-0" />
                            <div class="text-xs">
                                <span class="font-bold text-emerald-900">Kamu telah menyelesaikan skenario ini sebelumnya.</span>
                                <span class="text-emerald-700 block">Ingin mencoba pilihan berbeda untuk melihat cabang akhir yang lain?</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <Link
                            :href="route('lifequest.play', quest.slug)"
                            class="px-8 py-3.5 rounded-2xl bg-sunshine text-forest font-extrabold text-sm hover:bg-sunshine-300 transition shadow-lg flex items-center gap-2"
                        >
                            <Play class="w-4 h-4 fill-forest" />
                            <span>{{ lastAttempt ? 'Mainkan Ulang Skenario' : 'Mulai Petualangan Quest' }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
