<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Clock, 
    BookOpen, 
    CheckCircle2, 
    Circle, 
    Award, 
    ArrowLeft, 
    ChevronRight, 
    Sparkles,
    UserCheck,
    Play
} from 'lucide-vue-next';

const props = defineProps<{
    module: any;
    progress?: {
        total: number;
        completed: number;
        percent: number;
        is_completed: boolean;
    } | null;
    completedLessonIds: number[];
}>();

const firstIncompleteLesson = props.module.lessons.find((l: any) => !props.completedLessonIds.includes(l.id)) || props.module.lessons[0];
</script>

<template>
    <PublicLayout>
        <Head :title="`${module.title} - GenRe Academy`" />

        <div class="py-10 bg-gradient-to-b from-forest-50 to-[#F7F8F3]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Back Button -->
                <Link :href="route('academy.index')" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-forest transition">
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke Katalog Akademi</span>
                </Link>

                <!-- Hero Card -->
                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-sm space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <AppBadge variant="teal">{{ module.category?.name ?? 'Kurikulum' }}</AppBadge>
                        <div class="flex items-center gap-4 text-xs font-semibold text-gray-500">
                            <span class="flex items-center gap-1.5"><Clock class="w-4 h-4 text-gray-400" /> {{ module.estimated_minutes }} Menit Total</span>
                            <span class="uppercase tracking-wider px-2 py-0.5 rounded-md bg-forest-50 text-forest font-bold">{{ module.difficulty }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-2xl sm:text-4xl font-black text-forest tracking-tight">
                            {{ module.title }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                            {{ module.summary }}
                        </p>
                    </div>

                    <!-- Progress Bar (if logged in) -->
                    <div v-if="progress" class="p-5 rounded-2xl bg-forest-50 border border-forest-100 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-forest">
                            <span>Progres Belajarmu</span>
                            <span>{{ progress.completed }} dari {{ progress.total }} Materi ({{ progress.percent }}%)</span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-forest-200/60 overflow-hidden">
                            <div
                                class="h-full bg-gradient-to-r from-teal to-forest transition-all duration-500"
                                :style="{ width: `${progress.percent}%` }"
                            ></div>
                        </div>
                    </div>

                    <!-- Primary Action Button -->
                    <div v-if="firstIncompleteLesson" class="pt-2 flex items-center gap-3">
                        <Link
                            :href="route('academy.lesson', [module.slug, firstIncompleteLesson.id])"
                            class="px-6 py-3.5 rounded-2xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow flex items-center gap-2"
                        >
                            <Play class="w-4 h-4 fill-sunshine text-sunshine" />
                            <span>{{ progress?.completed ? 'Lanjutkan Belajar' : 'Mulai Pelajaran Pertama' }}</span>
                        </Link>
                    </div>
                </div>

                <!-- Syllabus / Lessons List -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold text-forest flex items-center gap-2">
                            <BookOpen class="w-5 h-5 text-teal" />
                            <span>Silabus Pelajaran</span>
                        </h2>
                        <span class="text-xs font-semibold text-gray-500">{{ module.lessons.length }} Bab Pembelajaran</span>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="(lesson, idx) in module.lessons"
                            :key="lesson.id"
                            class="bg-white rounded-2xl border p-5 flex items-center justify-between gap-4 transition hover:shadow-md"
                            :class="[
                                completedLessonIds.includes(lesson.id)
                                    ? 'border-emerald-200 bg-emerald-50/20'
                                    : 'border-forest/10'
                            ]"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0"
                                    :class="[
                                        completedLessonIds.includes(lesson.id)
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : 'bg-forest-50 text-forest'
                                    ]"
                                >
                                    <CheckCircle2 v-if="completedLessonIds.includes(lesson.id)" class="w-5 h-5 text-emerald-600" />
                                    <span v-else>{{ idx + 1 }}</span>
                                </div>
                                <div class="space-y-0.5">
                                    <h3 class="text-sm font-bold text-gray-900">{{ lesson.title }}</h3>
                                    <span class="text-xs text-gray-500 font-medium">{{ lesson.estimated_minutes }} Menit Baca</span>
                                </div>
                            </div>

                            <Link
                                :href="route('academy.lesson', [module.slug, lesson.id])"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5"
                                :class="[
                                    completedLessonIds.includes(lesson.id)
                                        ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'
                                        : 'bg-forest text-white hover:bg-forest-600 shadow-sm'
                                ]"
                            >
                                <span>{{ completedLessonIds.includes(lesson.id) ? 'Ulas Kembali' : 'Buka Materi' }}</span>
                                <ChevronRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Module Quiz Card -->
                <div v-if="module.quizzes?.length" class="p-6 sm:p-8 bg-gradient-to-r from-forest to-teal text-white rounded-3xl shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center sm:text-left">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sunshine text-forest text-xs font-black uppercase">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Uji Pemahaman</span>
                        </div>
                        <h3 class="text-xl font-black text-white">{{ module.quizzes[0].title }}</h3>
                        <p class="text-xs text-forest-100">
                            Passing score kelulusan: <strong>{{ module.quizzes[0].passing_score }}%</strong>. Dapatkan <strong>+40 XP</strong> dan badge jika berhasil lulus!
                        </p>
                    </div>

                    <Link
                        :href="route('quizzes.show', module.quizzes[0].id)"
                        class="px-7 py-3.5 rounded-2xl bg-sunshine text-forest font-extrabold text-sm hover:bg-sunshine-300 transition shadow-md shrink-0 flex items-center gap-2"
                    >
                        <Award class="w-5 h-5 text-forest" />
                        <span>Mulai Kuis Modul</span>
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
