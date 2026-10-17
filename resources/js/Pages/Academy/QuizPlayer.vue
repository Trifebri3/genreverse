<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Award, 
    CheckCircle2, 
    XCircle, 
    ArrowLeft, 
    ArrowRight, 
    Sparkles, 
    RotateCcw,
    HelpCircle
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps<{
    quiz: any;
    previousAttempts: any[];
}>();

const currentQuestionIndex = ref(0);
const selectedAnswers = ref<Record<number, number>>({});
const attemptId = ref<number | null>(null);
const isSubmitting = ref(false);
const quizResult = ref<any | null>(null);

const questions = computed(() => props.quiz.questions || []);
const currentQuestion = computed(() => questions.value[currentQuestionIndex.value]);
const isAnsweredAll = computed(() => {
    return questions.value.every((q: any) => selectedAnswers.value[q.id] !== undefined);
});

async function startQuiz() {
    try {
        const res = await axios.post(route('quizzes.attempts.start', props.quiz.id));
        attemptId.value = res.data.attempt_id;
    } catch (err) {
        console.error('Error starting attempt', err);
    }
}

// Auto start attempt on mount
startQuiz();

function selectOption(questionId: number, optionId: number) {
    selectedAnswers.value[questionId] = optionId;
}

function nextQuestion() {
    if (currentQuestionIndex.value < questions.value.length - 1) {
        currentQuestionIndex.value++;
    }
}

function prevQuestion() {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
}

async function submitQuiz() {
    if (!attemptId.value) return;
    isSubmitting.value = true;
    try {
        const res = await axios.post(route('quiz_attempts.submit', attemptId.value), {
            answers: selectedAnswers.value,
        });
        quizResult.value = res.data;
    } catch (err) {
        console.error('Error submitting quiz', err);
    } finally {
        isSubmitting.value = false;
    }
}

function restartQuiz() {
    selectedAnswers.value = {};
    currentQuestionIndex.value = 0;
    quizResult.value = null;
    startQuiz();
}
</script>

<template>
    <PublicLayout>
        <Head :title="`Kuis: ${quiz.title}`" />

        <div class="py-12 bg-gradient-to-b from-forest-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Top Breadcrumb -->
                <div class="flex items-center justify-between">
                    <Link
                        v-if="quiz.module"
                        :href="route('academy.show', quiz.module.slug)"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-forest transition"
                    >
                        <ArrowLeft class="w-4 h-4" />
                        <span>Kembali ke {{ quiz.module.title }}</span>
                    </Link>
                    <AppBadge variant="sunshine" size="sm">Passing Score: {{ quiz.passing_score }}%</AppBadge>
                </div>

                <!-- QUIZ RESULT SCREEN (After submission) -->
                <div v-if="quizResult" class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-lg space-y-8 animate-fade-in">
                    <div class="text-center space-y-3">
                        <div
                            class="w-20 h-20 mx-auto rounded-full flex items-center justify-center font-black text-3xl shadow-md"
                            :class="quizResult.passed ? 'bg-emerald-100 text-emerald-800 border-2 border-emerald-300' : 'bg-rose-100 text-rose-800 border-2 border-rose-300'"
                        >
                            {{ quizResult.score }}%
                        </div>
                        <h2 class="text-2xl font-black text-forest">
                            {{ quizResult.passed ? 'Luar Biasa! Kamu Lulus Kuis' : 'Belum Mencapai Nilai Kelulusan' }}
                        </h2>
                        <p class="text-sm text-gray-600 max-w-md mx-auto">
                            {{ quizResult.passed 
                                ? `Selamat! Kamu berhasil menjawab ${quizResult.correct_count} dari ${quizResult.total_questions} pertanyaan dengan benar. +40 XP telah dikreditkan ke profilmu.`
                                : `Kamu menjawab ${quizResult.correct_count} dari ${quizResult.total_questions} pertanyaan dengan tepat. Jangan berkecil hati, pelajari ulasan di bawah dan coba lagi!` 
                            }}
                        </p>
                    </div>

                    <!-- Question Breakdown / Explanations -->
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <h3 class="text-base font-bold text-forest">Ulasan & Penjelasan Jawaban:</h3>
                        <div
                            v-for="(detail, i) in quizResult.details"
                            :key="i"
                            class="p-4 rounded-2xl border text-sm space-y-2"
                            :class="detail.is_correct ? 'bg-emerald-50/50 border-emerald-200' : 'bg-rose-50/50 border-rose-200'"
                        >
                            <div class="flex items-start gap-2.5">
                                <CheckCircle2 v-if="detail.is_correct" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                                <XCircle v-else class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" />
                                <div>
                                    <div class="font-bold text-gray-900">{{ detail.question_text }}</div>
                                    <div v-if="!detail.is_correct && detail.correct_option_text" class="text-xs text-emerald-700 font-semibold mt-1">
                                        Jawaban benar: {{ detail.correct_option_text }}
                                    </div>
                                    <p v-if="detail.explanation" class="text-xs text-gray-600 mt-1 italic">
                                        💡 {{ detail.explanation }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Result Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
                        <button
                            @click="restartQuiz"
                            class="w-full sm:w-auto px-6 py-3 rounded-2xl border border-forest/20 text-forest font-bold text-sm hover:bg-forest-50 transition flex items-center justify-center gap-2"
                        >
                            <RotateCcw class="w-4 h-4" />
                            <span>Ulangi Kuis</span>
                        </button>
                        <Link
                            v-if="quiz.module"
                            :href="route('academy.show', quiz.module.slug)"
                            class="w-full sm:w-auto px-7 py-3 rounded-2xl bg-forest text-white font-bold text-sm hover:bg-forest-600 transition flex items-center justify-center gap-2"
                        >
                            <span>Selesai & Lanjut</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

                <!-- QUIZ PLAYER SCREEN (Active Attempt) -->
                <div v-else-if="currentQuestion" class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-sm space-y-8">
                    <!-- Progress Header -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-500">
                            <span>Pertanyaan {{ currentQuestionIndex + 1 }} dari {{ questions.length }}</span>
                            <span class="text-teal font-extrabold">{{ Math.round(((currentQuestionIndex + 1) / questions.length) * 100) }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div
                                class="h-full bg-forest transition-all duration-300"
                                :style="{ width: `${((currentQuestionIndex + 1) / questions.length) * 100}%` }"
                            ></div>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="space-y-4">
                        <h2 class="text-lg sm:text-xl font-bold text-forest leading-snug">
                            {{ currentQuestion.question_text }}
                        </h2>

                        <!-- Options -->
                        <div class="space-y-3 pt-2">
                            <button
                                v-for="opt in currentQuestion.options"
                                :key="opt.id"
                                type="button"
                                @click="selectOption(currentQuestion.id, opt.id)"
                                class="w-full text-left p-4 rounded-2xl border transition-all flex items-center justify-between gap-3 text-sm"
                                :class="[
                                    selectedAnswers[currentQuestion.id] === opt.id
                                        ? 'bg-forest text-white border-forest shadow-md font-semibold'
                                        : 'bg-[#F7F8F3] hover:bg-forest-50/70 border-forest/10 text-gray-800'
                                ]"
                            >
                                <span>{{ opt.option_text }}</span>
                                <div
                                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                    :class="selectedAnswers[currentQuestion.id] === opt.id ? 'border-sunshine bg-sunshine text-forest' : 'border-gray-400'"
                                >
                                    <div v-if="selectedAnswers[currentQuestion.id] === opt.id" class="w-2 h-2 rounded-full bg-forest"></div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Bottom Nav Controls -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-between gap-4">
                        <button
                            type="button"
                            @click="prevQuestion"
                            :disabled="currentQuestionIndex === 0"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition disabled:opacity-40"
                        >
                            Sebelumnya
                        </button>

                        <div class="flex items-center gap-2">
                            <button
                                v-if="currentQuestionIndex < questions.length - 1"
                                type="button"
                                @click="nextQuestion"
                                class="px-5 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition flex items-center gap-1.5 shadow"
                            >
                                <span>Lanjut</span>
                                <ArrowRight class="w-3.5 h-3.5" />
                            </button>
                            <button
                                v-else
                                type="button"
                                @click="submitQuiz"
                                :disabled="!isAnsweredAll || isSubmitting"
                                class="px-6 py-2.5 rounded-xl bg-sunshine text-forest text-xs font-extrabold hover:bg-sunshine-300 transition shadow flex items-center gap-1.5 disabled:opacity-50"
                            >
                                <Sparkles class="w-4 h-4" />
                                <span>{{ isSubmitting ? 'Memeriksa...' : 'Kirim Jawaban Kuis' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
