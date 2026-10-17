<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Clock, 
    ArrowLeft, 
    ChevronLeft, 
    ChevronRight, 
    CheckCircle2, 
    Sparkles, 
    Award,
    BookOpen
} from 'lucide-vue-next';

const props = defineProps<{
    module: any;
    lesson: any;
    isCompleted: boolean;
    previousLesson?: any;
    nextLesson?: any;
    quiz?: any;
}>();

const page = usePage();
const auth = page.props.auth as any;

const completeForm = useForm({});

function submitComplete() {
    completeForm.post(route('lessons.complete', props.lesson.id));
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${lesson.title} - ${module.title}`" />

        <div class="py-10 bg-[#F7F8F3] min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Navigation Breadcrumb -->
                <div class="flex items-center justify-between">
                    <Link :href="route('academy.show', module.slug)" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-forest transition">
                        <ArrowLeft class="w-4 h-4" />
                        <span>Kembali ke {{ module.title }}</span>
                    </Link>
                    <span class="text-xs font-semibold text-gray-500 flex items-center gap-1">
                        <Clock class="w-3.5 h-3.5" />
                        <span>{{ lesson.estimated_minutes }} Menit Baca</span>
                    </span>
                </div>

                <!-- Main Reading Card -->
                <article class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-12 shadow-sm space-y-8">
                    <!-- Lesson Header -->
                    <div class="space-y-3 pb-6 border-b border-gray-100">
                        <AppBadge variant="teal" size="sm">{{ module.category?.name ?? 'Materi' }}</AppBadge>
                        <h1 class="text-2xl sm:text-3xl font-black text-forest leading-tight">
                            {{ lesson.title }}
                        </h1>
                    </div>

                    <!-- Lesson Body Content (Clean formatting) -->
                    <div class="prose max-w-none text-gray-800 leading-relaxed space-y-4 text-sm sm:text-base whitespace-pre-line font-sans">
                        {{ lesson.content_body }}
                    </div>

                    <!-- Completion Action Box -->
                    <div class="pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 p-6 rounded-2xl bg-forest-50 border border-forest-100">
                        <div class="space-y-1 text-center sm:text-left">
                            <div class="text-xs font-bold text-teal uppercase tracking-wide">Pencapaian Belajar</div>
                            <h4 class="text-base font-extrabold text-forest">
                                {{ isCompleted ? 'Materi Ini Sudah Kamu Selesaikan!' : 'Sudah Memahami Materi Ini?' }}
                            </h4>
                            <p class="text-xs text-gray-600">
                                {{ isCompleted ? 'Kamu telah mengklaim +20 XP untuk materi ini.' : 'Tandai selesai untuk menyimpan progres dan klaim +20 XP.' }}
                            </p>
                        </div>

                        <template v-if="auth?.user">
                            <button
                                v-if="!isCompleted"
                                @click="submitComplete"
                                :disabled="completeForm.processing"
                                class="px-6 py-3 rounded-xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow flex items-center gap-2 shrink-0 disabled:opacity-50"
                            >
                                <Sparkles class="w-4 h-4 text-sunshine" />
                                <span>{{ completeForm.processing ? 'Menyimpan...' : 'Selesaikan & Klaim +20 XP' }}</span>
                            </button>
                            <div v-else class="px-5 py-2.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-2 shrink-0 border border-emerald-200">
                                <CheckCircle2 class="w-4 h-4 text-emerald-600" />
                                <span>Tuntas Diselesaikan</span>
                            </div>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('register')"
                                class="px-6 py-3 rounded-xl bg-sunshine text-forest font-extrabold text-xs hover:bg-sunshine-300 transition shadow shrink-0"
                            >
                                Daftar untuk Simpan Progres (+20 XP)
                            </Link>
                        </template>
                    </div>

                    <!-- Next & Previous Navigation -->
                    <div class="pt-4 flex items-center justify-between gap-4">
                        <div>
                            <Link
                                v-if="previousLesson"
                                :href="route('academy.lesson', [module.slug, previousLesson.id])"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 hover:border-forest hover:text-forest transition flex items-center gap-2"
                            >
                                <ChevronLeft class="w-4 h-4" />
                                <span class="hidden sm:inline">Sebelumnya: {{ previousLesson.title }}</span>
                                <span class="sm:hidden">Sebelumnya</span>
                            </Link>
                        </div>

                        <div>
                            <Link
                                v-if="nextLesson"
                                :href="route('academy.lesson', [module.slug, nextLesson.id])"
                                class="px-5 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition flex items-center gap-2 shadow"
                            >
                                <span class="hidden sm:inline">Selanjutnya: {{ nextLesson.title }}</span>
                                <span class="sm:hidden">Selanjutnya</span>
                                <ChevronRight class="w-4 h-4" />
                            </Link>
                            <Link
                                v-else-if="quiz"
                                :href="route('quizzes.show', quiz.id)"
                                class="px-6 py-2.5 rounded-xl bg-sunshine text-forest text-xs font-black hover:bg-sunshine-400 transition flex items-center gap-2 shadow"
                            >
                                <Award class="w-4 h-4" />
                                <span>Ikuti Kuis Modul</span>
                                <ChevronRight class="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </PublicLayout>
</template>
