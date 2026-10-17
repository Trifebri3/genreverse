<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Compass, 
    Sparkles, 
    RotateCcw, 
    ArrowRight, 
    CheckCircle2, 
    AlertCircle, 
    Award,
    HeartHandshake,
    ChevronRight,
    Gamepad2
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps<{
    quest: any;
    attempt: any;
    currentStage: any;
}>();

const stage = ref(props.currentStage);
const isEnding = ref(props.currentStage?.is_ending || false);
const isChoosing = ref(false);
const choiceModal = ref<{
    open: boolean;
    feedback: string;
    impact: string;
    nextStage: any;
    isCompleted: boolean;
    xpReward: number;
} | null>(null);

async function makeChoice(choiceId: number) {
    if (isChoosing.value) return;
    isChoosing.value = true;
    try {
        const res = await axios.post(route('quest_attempts.choose', props.attempt.id), {
            stage_id: stage.value.id,
            choice_id: choiceId,
        });

        choiceModal.value = {
            open: true,
            feedback: res.data.choice_feedback,
            impact: res.data.impact_summary,
            nextStage: res.data.next_stage,
            isCompleted: res.data.is_completed,
            xpReward: res.data.xp_reward,
        };
    } catch (err) {
        console.error('Error submitting choice', err);
    } finally {
        isChoosing.value = false;
    }
}

function proceedToNextStage() {
    if (!choiceModal.value) return;
    const next = choiceModal.value.nextStage;
    const completed = choiceModal.value.isCompleted;

    choiceModal.value = null;

    if (completed || !next) {
        isEnding.value = true;
        if (next) {
            stage.value = next;
        }
    } else {
        stage.value = next;
        isEnding.value = next.is_ending;
    }
}

function restartQuest() {
    router.post(route('lifequest.attempts.start', props.quest.id), {}, {
        onSuccess: () => {
            window.location.reload();
        }
    });
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${quest.title} - Mainkan LifeQuest`" />

        <div class="py-10 bg-gradient-to-b from-forest-900 via-forest-800 to-forest-900 text-white min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Top Progress Bar -->
                <div class="flex items-center justify-between text-xs text-forest-200">
                    <Link :href="route('lifequest.index')" class="hover:text-white transition flex items-center gap-1 font-bold">
                        <span>&larr; Keluar dari Simulasi</span>
                    </Link>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-sunshine animate-ping"></span>
                        <span class="font-bold text-sunshine">Skenario Interaktif Aktif</span>
                    </div>
                </div>

                <!-- ENDING REFLECTION SCREEN -->
                <div v-if="isEnding" class="bg-white text-gray-900 rounded-3xl p-6 sm:p-12 shadow-2xl border-2 border-sunshine space-y-8 animate-fade-in">
                    <div class="text-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-sunshine-100 text-forest mx-auto flex items-center justify-center font-black text-2xl shadow">
                            <Sparkles class="w-8 h-8 text-sunshine-700" />
                        </div>
                        <AppBadge variant="sunshine">Misi Selesai!</AppBadge>
                        <h2 class="text-2xl sm:text-3xl font-black text-forest">
                            {{ stage?.title ?? 'Akhir Perjalananmu' }}
                        </h2>
                    </div>

                    <!-- Story Ending & GenRe Takeaway -->
                    <div class="p-6 rounded-2xl bg-forest-50 border border-forest-100 text-sm sm:text-base leading-relaxed text-gray-800 whitespace-pre-line">
                        {{ stage?.story_content }}
                    </div>

                    <!-- XP Reward Banner -->
                    <div class="p-5 rounded-2xl bg-gradient-to-r from-forest to-teal text-white flex items-center justify-between shadow">
                        <div class="flex items-center gap-3">
                            <Award class="w-8 h-8 text-sunshine" />
                            <div>
                                <div class="text-xs font-bold text-sunshine uppercase">Pencapaian Keputusan</div>
                                <div class="text-base font-extrabold">+{{ quest.xp_reward }} XP & Badge Penakluk LifeQuest</div>
                            </div>
                        </div>
                        <Sparkles class="w-6 h-6 text-sunshine animate-spin" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <button
                            @click="restartQuest"
                            class="w-full sm:w-auto px-6 py-3.5 rounded-2xl border-2 border-forest/20 text-forest font-bold text-sm hover:bg-forest-50 transition flex items-center justify-center gap-2"
                        >
                            <RotateCcw class="w-4 h-4" />
                            <span>Coba Pilihan Cabang Lain</span>
                        </button>
                        <Link
                            :href="route('dashboard')"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-sunshine text-forest font-extrabold text-sm hover:bg-sunshine-300 transition shadow-lg flex items-center justify-center gap-2"
                        >
                            <span>Lihat Progres di Dashboard</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <!-- ACTIVE STORY STAGE CARD -->
                <div v-else-if="stage" class="bg-white/95 backdrop-blur-xl text-gray-900 rounded-3xl p-6 sm:p-10 shadow-2xl border border-white/20 space-y-8 animate-fade-in">
                    <!-- Stage Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <AppBadge variant="forest" size="sm">Adegan Cerita</AppBadge>
                        <span class="text-xs font-bold text-teal flex items-center gap-1.5">
                            <Gamepad2 class="w-4 h-4" />
                            <span>Pilih Keputusanmu</span>
                        </span>
                    </div>

                    <!-- Title & Narrative Text -->
                    <div class="space-y-4">
                        <h2 class="text-xl sm:text-2xl font-black text-forest leading-snug">
                            {{ stage.title }}
                        </h2>
                        <div class="text-sm sm:text-base text-gray-800 leading-relaxed whitespace-pre-line p-5 rounded-2xl bg-forest-50/70 border border-forest-100/60 font-sans">
                            {{ stage.story_content }}
                        </div>
                    </div>

                    <!-- Choices Section -->
                    <div class="space-y-3 pt-2">
                        <div class="text-xs font-black uppercase tracking-wider text-forest">
                            Apa yang akan kamu lakukan?
                        </div>

                        <div class="space-y-3">
                            <button
                                v-for="(choice, idx) in stage.choices"
                                :key="choice.id"
                                type="button"
                                @click="makeChoice(choice.id)"
                                :disabled="isChoosing"
                                class="w-full text-left p-4 sm:p-5 rounded-2xl border border-forest/15 bg-white hover:bg-sunshine-50 hover:border-sunshine transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-between gap-4 group"
                            >
                                <div class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full bg-forest text-sunshine text-xs font-black flex items-center justify-center shrink-0 mt-0.5 group-hover:scale-110 transition-transform">
                                        {{ String.fromCharCode(65 + idx) }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-forest">
                                        {{ choice.choice_text }}
                                    </span>
                                </div>
                                <ChevronRight class="w-5 h-5 text-gray-400 group-hover:text-forest group-hover:translate-x-1 transition-all shrink-0" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CHOICE FEEDBACK MODAL DIALOG -->
                <div v-if="choiceModal?.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full text-gray-900 shadow-2xl border-2 border-sunshine space-y-6 animate-scale-up">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-sunshine-100 text-forest flex items-center justify-center font-black">
                                💡
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-forest">Dampak Pilihanmu</h3>
                                <p class="text-xs text-gray-500">Konsekuensi dalam skenario simulasi</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="p-4 rounded-xl bg-forest-50 border border-forest-100 text-forest-900 leading-relaxed">
                                {{ choiceModal.feedback }}
                            </div>

                            <div v-if="choiceModal.impact" class="text-xs font-semibold text-gray-600 flex items-center gap-1.5">
                                <AlertCircle class="w-4 h-4 text-amber-600" />
                                <span>Pengaruh: {{ choiceModal.impact }}</span>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="proceedToNextStage"
                            class="w-full py-3.5 rounded-2xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow flex items-center justify-center gap-2"
                        >
                            <span>{{ choiceModal.isCompleted ? 'Lihat Refleksi Akhir' : 'Lanjutkan ke Adegan Berikutnya' }}</span>
                            <ArrowRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
