<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Compass, 
    Sparkles, 
    ChevronRight, 
    RotateCcw, 
    ArrowRight, 
    Gamepad2,
    Award
} from 'lucide-vue-next';

const props = defineProps<{
    quest: any;
}>();

const currentStageIndex = ref(0);
const stages = ref(props.quest.stages || []);
const stage = ref(stages.value[0]);
const isEnding = ref(stage.value?.is_ending || false);
const feedbackText = ref('');
const showFeedback = ref(false);

function pickChoice(choice: any) {
    feedbackText.value = choice.feedback;
    showFeedback.value = true;

    // Find next stage
    const next = stages.value.find((s: any) => s.id === choice.next_stage_id);
    if (next) {
        stage.value = next;
        isEnding.value = next.is_ending;
    } else {
        // Find ending
        const ending = stages.value.find((s: any) => s.is_ending) || stages.value[stages.value.length - 1];
        stage.value = ending;
        isEnding.value = true;
    }
}

function restartGuest() {
    stage.value = stages.value[0];
    isEnding.value = false;
    showFeedback.value = false;
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${quest.title} (Mode Eksplorasi Guest)`" />

        <div class="py-10 bg-gradient-to-b from-forest-900 via-forest-800 to-forest-900 text-white min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Guest Banner -->
                <div class="p-3.5 rounded-2xl bg-sunshine/10 border border-sunshine/30 flex items-center justify-between text-xs text-sunshine">
                    <span>💡 Kamu sedang bermain sebagai <strong>Tamu (Guest)</strong>.</span>
                    <Link :href="route('register')" class="font-extrabold underline hover:text-white">
                        Daftar untuk Simpan XP & Badge &rarr;
                    </Link>
                </div>

                <!-- ENDING -->
                <div v-if="isEnding" class="bg-white text-gray-900 rounded-3xl p-6 sm:p-12 shadow-2xl border-2 border-sunshine space-y-8 animate-fade-in">
                    <div class="text-center space-y-3">
                        <AppBadge variant="sunshine">Akhir Cerita Simulasi</AppBadge>
                        <h2 class="text-2xl sm:text-3xl font-black text-forest">
                            {{ stage.title }}
                        </h2>
                    </div>

                    <div class="p-6 rounded-2xl bg-forest-50 border border-forest-100 text-sm sm:text-base leading-relaxed text-gray-800 whitespace-pre-line">
                        {{ stage.story_content }}
                    </div>

                    <div class="p-5 rounded-2xl bg-gradient-to-r from-forest to-teal text-white flex items-center justify-between">
                        <div class="space-y-0.5">
                            <div class="text-xs font-bold text-sunshine">Mau klaim +{{ quest.xp_reward }} XP dan lencana resmi?</div>
                            <div class="text-sm font-extrabold">Buat akun dalam 30 detik untuk mencatat pencapaianmu.</div>
                        </div>
                        <Link :href="route('register')" class="px-5 py-2.5 rounded-xl bg-sunshine text-forest font-black text-xs hover:bg-sunshine-300 transition shadow">
                            Daftar Sekarang
                        </Link>
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <button
                            @click="restartGuest"
                            class="px-6 py-3 rounded-2xl border border-forest/20 text-forest font-bold text-sm hover:bg-forest-50 transition flex items-center gap-2"
                        >
                            <RotateCcw class="w-4 h-4" />
                            <span>Coba Pilihan Lain</span>
                        </button>
                    </div>
                </div>

                <!-- ACTIVE STAGE -->
                <div v-else-if="stage" class="bg-white text-gray-900 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 animate-fade-in">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <AppBadge variant="forest">LifeQuest Simulation</AppBadge>
                        <span class="text-xs font-bold text-teal">Ambil Keputusan</span>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl sm:text-2xl font-black text-forest">
                            {{ stage.title }}
                        </h2>
                        <div class="text-sm sm:text-base text-gray-800 leading-relaxed whitespace-pre-line p-5 rounded-2xl bg-forest-50/70 border border-forest-100 font-sans">
                            {{ stage.story_content }}
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="text-xs font-black uppercase tracking-wider text-forest">
                            Apa keputusan yang kamu ambil?
                        </div>
                        <div class="space-y-3">
                            <button
                                v-for="(choice, idx) in stage.choices"
                                :key="choice.id"
                                type="button"
                                @click="pickChoice(choice)"
                                class="w-full text-left p-4 sm:p-5 rounded-2xl border border-forest/15 hover:bg-sunshine-50 hover:border-sunshine transition-all duration-200 flex items-center justify-between gap-4 group shadow-sm"
                            >
                                <div class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full bg-forest text-sunshine text-xs font-black flex items-center justify-center shrink-0 mt-0.5">
                                        {{ String.fromCharCode(65 + idx) }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-forest">
                                        {{ choice.choice_text }}
                                    </span>
                                </div>
                                <ChevronRight class="w-5 h-5 text-gray-400 group-hover:text-forest transition shrink-0" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
