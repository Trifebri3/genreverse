<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import AppPagination from '@/Components/AppPagination.vue';
import AppEmptyState from '@/Components/AppEmptyState.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { 
    ShieldAlert, 
    BookOpen, 
    HelpCircle, 
    AlertTriangle, 
    CheckCircle2, 
    XCircle, 
    MessageSquare, 
    Trash2,
    EyeOff,
    Check
} from 'lucide-vue-next';

interface ContentReport {
    id: number;
    reporter_id: number | null;
    content_type: string;
    content_id: number;
    reason: string;
    description: string | null;
    status: string;
    created_at: string;
    reporter?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

interface Story {
    id: number;
    title: string;
    body: string;
    publication_mode: string;
    status: string;
    created_at: string;
    author?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

interface Question {
    id: number;
    question_body: string;
    public_reference: string;
    is_anonymous: boolean;
    status: string;
    created_at: string;
    user?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

const props = defineProps<{
    pendingReports: {
        data: ContentReport[];
        links: any[];
        total: number;
    };
    pendingStories: {
        data: Story[];
        links: any[];
        total: number;
    };
    pendingQuestions: {
        data: Question[];
        links: any[];
        total: number;
    };
}>();

const activeTab = ref<'reports' | 'stories' | 'questions'>('reports');

// Report resolution state
const selectedReport = ref<ContentReport | null>(null);
const isReportModalOpen = ref(false);
const reportForm = useForm({
    action: 'hidden',
    reason: '',
});

const openReportModal = (report: ContentReport) => {
    selectedReport.value = report;
    reportForm.action = 'hidden';
    reportForm.reason = '';
    isReportModalOpen.value = true;
};

const closeReportModal = () => {
    isReportModalOpen.value = false;
    selectedReport.value = null;
    reportForm.reset();
};

const submitReportResolution = () => {
    if (!selectedReport.value) return;

    reportForm.post(route('admin.moderation.resolve_report', selectedReport.value.id), {
        onSuccess: () => {
            closeReportModal();
        },
    });
};

// Story moderation
const storyForm = useForm({
    approved: true,
});

const handleStoryModeration = (story: Story, approved: boolean) => {
    storyForm.approved = approved;
    storyForm.post(route('admin.moderation.story', story.id));
};

// Question moderation
const questionForm = useForm({
    approved: true,
});

const handleQuestionModeration = (question: Question, approved: boolean) => {
    questionForm.approved = approved;
    questionForm.post(route('admin.moderation.question', question.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Pusat Moderasi & Perlindungan Konten - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <ShieldAlert class="w-6 h-6 text-rose-600" />
                        <span>Pusat Moderasi & Perlindungan Remaja</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Penyaringan konten tidak pantas, verifikasi cerita inspiratif, dan penanganan pertanyaan remaja.
                    </p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 gap-2">
                <button
                    @click="activeTab = 'reports'"
                    class="px-4 py-2.5 text-xs font-bold border-b-2 transition flex items-center gap-2"
                    :class="[
                        activeTab === 'reports'
                            ? 'border-rose-600 text-rose-700'
                            : 'border-transparent text-gray-500 hover:text-gray-900'
                    ]"
                >
                    <AlertTriangle class="w-4 h-4" />
                    <span>Laporan Konten</span>
                    <span 
                        v-if="pendingReports.total > 0"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-rose-100 text-rose-800 font-black"
                    >
                        {{ pendingReports.total }}
                    </span>
                </button>

                <button
                    @click="activeTab = 'stories'"
                    class="px-4 py-2.5 text-xs font-bold border-b-2 transition flex items-center gap-2"
                    :class="[
                        activeTab === 'stories'
                            ? 'border-forest text-forest'
                            : 'border-transparent text-gray-500 hover:text-gray-900'
                    ]"
                >
                    <BookOpen class="w-4 h-4" />
                    <span>Kurasi Cerita Kita</span>
                    <span 
                        v-if="pendingStories.total > 0"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-forest-100 text-forest font-black"
                    >
                        {{ pendingStories.total }}
                    </span>
                </button>

                <button
                    @click="activeTab = 'questions'"
                    class="px-4 py-2.5 text-xs font-bold border-b-2 transition flex items-center gap-2"
                    :class="[
                        activeTab === 'questions'
                            ? 'border-teal text-teal'
                            : 'border-transparent text-gray-500 hover:text-gray-900'
                    ]"
                >
                    <HelpCircle class="w-4 h-4" />
                    <span>Kurasi SAPA GenRe</span>
                    <span 
                        v-if="pendingQuestions.total > 0"
                        class="px-2 py-0.5 rounded-full text-[10px] bg-teal-100 text-teal font-black"
                    >
                        {{ pendingQuestions.total }}
                    </span>
                </button>
            </div>

            <!-- TAB 1: Content Reports -->
            <div v-if="activeTab === 'reports'" class="space-y-4">
                <div v-if="pendingReports.data.length > 0" class="space-y-3">
                    <div
                        v-for="report in pendingReports.data"
                        :key="report.id"
                        class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                                        {{ report.content_type }} #{{ report.content_id }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-800">Alasan: {{ report.reason }}</span>
                                </div>
                                <p class="text-xs text-gray-600 bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                    "{{ report.description || 'Tidak ada deskripsi rinci dari pelapor.' }}"
                                </p>
                                <div class="text-[11px] text-gray-400">
                                    Pelapor: {{ report.reporter?.profile?.display_name ?? report.reporter?.name ?? 'Anonim' }} &bull;
                                    Waktu: {{ new Date(report.created_at).toLocaleString('id-ID') }}
                                </div>
                            </div>

                            <button
                                @click="openReportModal(report)"
                                class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm shrink-0"
                            >
                                <ShieldAlert class="w-3.5 h-3.5" />
                                <span>Tindak Lanjuti</span>
                            </button>
                        </div>
                    </div>

                    <AppPagination :links="pendingReports.links" />
                </div>
                <AppEmptyState
                    v-else
                    title="Antrean Laporan Bersih"
                    description="Tidak ada laporan pelanggaran konten yang menunggu tindak lanjut."
                    :icon="CheckCircle2"
                />
            </div>

            <!-- TAB 2: Story Curation -->
            <div v-if="activeTab === 'stories'" class="space-y-4">
                <div v-if="pendingStories.data.length > 0" class="space-y-4">
                    <div
                        v-for="story in pendingStories.data"
                        :key="story.id"
                        class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-extrabold px-2 py-0.5 rounded bg-forest-50 text-forest border border-forest-100">
                                        Mode: {{ story.publication_mode }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Penulis: <strong>{{ story.author?.profile?.display_name ?? story.author?.name }}</strong>
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">{{ story.title }}</h3>
                                <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line bg-gray-50/70 p-3 rounded-xl border border-gray-100">
                                    {{ story.body }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button
                                    @click="handleStoryModeration(story, true)"
                                    class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <CheckCircle2 class="w-3.5 h-3.5" />
                                    <span>Setujui</span>
                                </button>
                                <button
                                    @click="handleStoryModeration(story, false)"
                                    class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <XCircle class="w-3.5 h-3.5" />
                                    <span>Tolak</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <AppPagination :links="pendingStories.links" />
                </div>
                <AppEmptyState
                    v-else
                    title="Tidak Ada Cerita Menunggu"
                    description="Semua cerita remaja yang diajukan telah selesai dikurasi."
                    :icon="CheckCircle2"
                />
            </div>

            <!-- TAB 3: Question Curation -->
            <div v-if="activeTab === 'questions'" class="space-y-4">
                <div v-if="pendingQuestions.data.length > 0" class="space-y-4">
                    <div
                        v-for="q in pendingQuestions.data"
                        :key="q.id"
                        class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-teal-50 text-teal border border-teal-200 font-mono">
                                        {{ q.public_reference }}
                                    </span>
                                    <span v-if="q.is_anonymous" class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                        Anonim
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ new Date(q.created_at).toLocaleDateString('id-ID') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-800 font-medium leading-relaxed bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                                    "{{ q.question_body }}"
                                </p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button
                                    @click="handleQuestionModeration(q, true)"
                                    class="px-3.5 py-1.5 rounded-xl bg-teal hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <CheckCircle2 class="w-3.5 h-3.5" />
                                    <span>Kirim ke Duta</span>
                                </button>
                                <button
                                    @click="handleQuestionModeration(q, false)"
                                    class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <XCircle class="w-3.5 h-3.5" />
                                    <span>Tolak / Spam</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <AppPagination :links="pendingQuestions.links" />
                </div>
                <AppEmptyState
                    v-else
                    title="Antrean Pertanyaan Selesai"
                    description="Tidak ada pertanyaan konsultasi remaja yang menunggu penyaringan awal."
                    :icon="CheckCircle2"
                />
            </div>
        </div>

        <!-- Resolution Modal for Content Report -->
        <Modal :show="isReportModalOpen" @close="closeReportModal">
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <ShieldAlert class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">
                            Tindak Lanjut Laporan Konten
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ selectedReport?.content_type }} #{{ selectedReport?.content_id }} &bull; Alasan: {{ selectedReport?.reason }}
                        </p>
                    </div>
                </div>

                <!-- Select Action -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">Tindakan Moderasi:</label>
                    <select
                        v-model="reportForm.action"
                        class="w-full text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                    >
                        <option value="hidden">Sembunyikan Konten (Takedown Sementara)</option>
                        <option value="deleted">Hapus Konten Permanen</option>
                        <option value="dismiss">Abaikan Laporan (Konten Dinilai Sah)</option>
                    </select>
                </div>

                <!-- Reason / Notes -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">Catatan Tindakan / Alasan:</label>
                    <textarea
                        v-model="reportForm.reason"
                        rows="3"
                        class="w-full text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                        placeholder="Tuliskan justifikasi keputusan moderasi..."
                        required
                    ></textarea>
                    <p v-if="reportForm.errors.reason" class="text-xs text-rose-600 font-medium">
                        {{ reportForm.errors.reason }}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeReportModal" :disabled="reportForm.processing">
                        Batal
                    </SecondaryButton>
                    <button
                        @click="submitReportResolution"
                        :disabled="reportForm.processing"
                        class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50"
                    >
                        {{ reportForm.processing ? 'Menyimpan...' : 'Eksekusi Tindakan' }}
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
