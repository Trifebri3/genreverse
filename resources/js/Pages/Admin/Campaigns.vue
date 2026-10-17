<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import AppPagination from '@/Components/AppPagination.vue';
import AppEmptyState from '@/Components/AppEmptyState.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { 
    Megaphone, 
    CheckCircle2, 
    XCircle, 
    Calendar, 
    MapPin, 
    User, 
    Eye,
    Target,
    HelpCircle,
    FileText
} from 'lucide-vue-next';

interface Campaign {
    id: number;
    title: string;
    slug: string;
    issue_category: string;
    problem_statement: string;
    objective: string;
    target_audience: string;
    call_to_action: string;
    planned_start: string | null;
    planned_end: string | null;
    status: string;
    created_at: string;
    creator?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
    region?: {
        name: string;
        level: string;
    };
    reviewer?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

const props = defineProps<{
    campaigns: {
        data: Campaign[];
        links: any[];
        total: number;
    };
    selectedStatus: string | null;
}>();

const statusTabs = [
    { label: 'Semua Status', value: null },
    { label: 'Menunggu Review', value: 'in_review' },
    { label: 'Terbit Publik', value: 'published' },
    { label: 'Draft Duta', value: 'draft' },
    { label: 'Perlu Revisi / Ditolak', value: 'rejected' },
];

const selectedCampaign = ref<Campaign | null>(null);
const isReviewModalOpen = ref(false);

const reviewForm = useForm({
    approved: true,
    reason: '',
});

const filterByStatus = (status: string | null) => {
    router.get(route('admin.campaigns.index'), { status: status || undefined }, {
        preserveState: true,
        replace: true,
    });
};

const openReviewModal = (campaign: Campaign, approve: boolean) => {
    selectedCampaign.value = campaign;
    reviewForm.approved = approve;
    reviewForm.reason = '';
    isReviewModalOpen.value = true;
};

const closeReviewModal = () => {
    isReviewModalOpen.value = false;
    selectedCampaign.value = null;
    reviewForm.reset();
};

const submitReview = () => {
    if (!selectedCampaign.value) return;

    reviewForm.post(route('admin.campaigns.review', selectedCampaign.value.id), {
        onSuccess: () => {
            closeReviewModal();
        },
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Review Kampanye Advokasi - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <Megaphone class="w-6 h-6 text-forest" />
                        <span>Manajemen & Review Kampanye</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Pemeriksaan rancangan advokasi sosial dari Duta GenRe sebelum dipublikasikan ke publik.
                    </p>
                </div>
                <div class="text-xs font-bold text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                    Total: <span class="text-forest font-black">{{ campaigns.total }}</span> Kampanye
                </div>
            </div>

            <!-- Filter Status Tabs -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
                <button
                    v-for="tab in statusTabs"
                    :key="tab.label"
                    @click="filterByStatus(tab.value)"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
                    :class="[
                        selectedStatus === tab.value || (!selectedStatus && tab.value === null)
                            ? 'bg-forest text-white shadow-sm'
                            : 'bg-white text-gray-600 border border-gray-200 hover:border-forest hover:text-forest'
                    ]"
                >
                    <span>{{ tab.label }}</span>
                </button>
            </div>

            <!-- Campaigns Table / Cards -->
            <div v-if="campaigns.data.length > 0" class="space-y-4">
                <div
                    v-for="camp in campaigns.data"
                    :key="camp.id"
                    class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:border-forest-300 transition space-y-4"
                >
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1.5 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-forest-50 text-forest border border-forest-100">
                                    {{ camp.issue_category }}
                                </span>
                                <StatusBadge :status="camp.status" />
                                <span v-if="camp.region" class="text-xs text-gray-500 flex items-center gap-1">
                                    <MapPin class="w-3 h-3 text-gray-400" />
                                    <span>{{ camp.region.name }}</span>
                                </span>
                            </div>
                            <h2 class="text-base font-bold text-gray-900">{{ camp.title }}</h2>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ camp.problem_statement }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 shrink-0">
                            <div v-if="camp.status === 'in_review'" class="flex items-center gap-1.5">
                                <button
                                    @click="openReviewModal(camp, true)"
                                    class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <CheckCircle2 class="w-3.5 h-3.5" />
                                    <span>Setujui</span>
                                </button>
                                <button
                                    @click="openReviewModal(camp, false)"
                                    class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-sm"
                                >
                                    <XCircle class="w-3.5 h-3.5" />
                                    <span>Tolak</span>
                                </button>
                            </div>

                            <Link
                                v-if="camp.status === 'published'"
                                :href="route('advocacy.show', camp.slug)"
                                target="_blank"
                                class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:border-forest hover:text-forest text-xs font-bold transition flex items-center gap-1"
                            >
                                <Eye class="w-3.5 h-3.5" />
                                <span>Lihat Publik</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Meta Details & Expandable Summary -->
                    <div class="pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-gray-500 bg-gray-50/70 p-3 rounded-xl">
                        <div class="flex items-center gap-2">
                            <User class="w-3.5 h-3.5 text-gray-400" />
                            <span>Inisiator: <strong class="text-gray-800">{{ camp.creator?.profile?.display_name ?? camp.creator?.name ?? 'Anonim' }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Target class="w-3.5 h-3.5 text-gray-400" />
                            <span>Target: <strong class="text-gray-800">{{ camp.target_audience }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Calendar class="w-3.5 h-3.5 text-gray-400" />
                            <span>Jadwal: <strong class="text-gray-800">{{ camp.planned_start ? new Date(camp.planned_start).toLocaleDateString('id-ID') : 'Belum diatur' }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <AppPagination :links="campaigns.links" />
            </div>

            <!-- Empty State -->
            <AppEmptyState
                v-else
                title="Tidak Ada Kampanye"
                description="Belum ada usulan kampanye advokasi untuk kategori status yang dipilih saat ini."
                :icon="Megaphone"
            />
        </div>

        <!-- Review Decision Modal -->
        <Modal :show="isReviewModalOpen" @close="closeReviewModal">
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div 
                        class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                        :class="reviewForm.approved ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                    >
                        <component :is="reviewForm.approved ? CheckCircle2 : XCircle" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">
                            {{ reviewForm.approved ? 'Setujui & Publikasikan Kampanye' : 'Tolak / Minta Revisi Kampanye' }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ selectedCampaign?.title }}
                        </p>
                    </div>
                </div>

                <!-- Notes / Reason -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">
                        {{ reviewForm.approved ? 'Catatan Apresiasi & Petunjuk (Opsional):' : 'Alasan Penolakan / Poin Revisi Wajib:' }}
                    </label>
                    <textarea
                        v-model="reviewForm.reason"
                        rows="3"
                        class="w-full text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                        :placeholder="reviewForm.approved ? 'Masukkan catatan untuk Duta pelaksana...' : 'Jelaskan bagian rencana aksi atau bahasa yang perlu disesuaikan...'"
                        :required="!reviewForm.approved"
                    ></textarea>
                    <p v-if="reviewForm.errors.reason" class="text-xs text-rose-600 font-medium">
                        {{ reviewForm.errors.reason }}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeReviewModal" :disabled="reviewForm.processing">
                        Batal
                    </SecondaryButton>
                    <button
                        @click="submitReview"
                        :disabled="reviewForm.processing"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white transition shadow-sm disabled:opacity-50"
                        :class="reviewForm.approved ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                    >
                        {{ reviewForm.processing ? 'Menyimpan...' : (reviewForm.approved ? 'Konfirmasi Setujui' : 'Konfirmasi Tolak') }}
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
