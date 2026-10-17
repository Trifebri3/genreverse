<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import AppPagination from '@/Components/AppPagination.vue';
import AppEmptyState from '@/Components/AppEmptyState.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { 
    FileCheck2, 
    Calendar, 
    Users, 
    CheckCircle2, 
    XCircle, 
    ExternalLink, 
    MapPin,
    AlertCircle,
    FileText
} from 'lucide-vue-next';

interface CampaignReport {
    id: number;
    campaign_id: number;
    activity_date: string;
    summary: string;
    claimed_participants: number;
    verified_participants: number | null;
    evidence_path: string | null;
    verification_status: string;
    created_at: string;
    campaign?: {
        title: string;
        region?: {
            name: string;
        };
    };
    submitter?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
    reviewer?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

const props = defineProps<{
    reports: {
        data: CampaignReport[];
        links: any[];
        total: number;
    };
}>();

const selectedReport = ref<CampaignReport | null>(null);
const isVerifyModalOpen = ref(false);

const verifyForm = useForm({
    verified: true,
    verified_participants: 0,
    notes: '',
});

const openVerifyModal = (report: CampaignReport) => {
    selectedReport.value = report;
    verifyForm.verified = true;
    verifyForm.verified_participants = report.claimed_participants || 0;
    verifyForm.notes = '';
    isVerifyModalOpen.value = true;
};

const closeVerifyModal = () => {
    isVerifyModalOpen.value = false;
    selectedReport.value = null;
    verifyForm.reset();
};

const submitVerification = () => {
    if (!selectedReport.value) return;

    verifyForm.post(route('admin.campaign_reports.verify', selectedReport.value.id), {
        onSuccess: () => {
            closeVerifyModal();
        },
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Verifikasi Laporan Kegiatan Advokasi - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <FileCheck2 class="w-6 h-6 text-teal" />
                        <span>Verifikasi Laporan Kegiatan Advokasi</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Verifikasi bukti pelaksanaan dan konfirmasi peserta riil untuk pencatatan di Impact Map Nasional.
                    </p>
                </div>
                <div class="text-xs font-bold text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                    Total: <span class="text-teal font-black">{{ reports.total }}</span> Laporan
                </div>
            </div>

            <!-- Reports List -->
            <div v-if="reports.data.length > 0" class="space-y-4">
                <div
                    v-for="rep in reports.data"
                    :key="rep.id"
                    class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-4"
                >
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-teal-50 text-teal border border-teal-100">
                                    {{ rep.campaign?.region?.name ?? 'Nasional' }}
                                </span>
                                <StatusBadge :status="rep.verification_status" />
                                <span class="text-xs text-gray-400">
                                    Diajukan: {{ new Date(rep.created_at).toLocaleDateString('id-ID') }}
                                </span>
                            </div>
                            <h2 class="text-base font-bold text-gray-900">{{ rep.campaign?.title }}</h2>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ rep.summary }}</p>
                        </div>

                        <!-- Verification Action -->
                        <div class="shrink-0 flex items-center gap-2">
                            <button
                                v-if="rep.verification_status === 'pending'"
                                @click="openVerifyModal(rep)"
                                class="px-4 py-2 rounded-xl bg-forest hover:bg-forest-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm"
                            >
                                <CheckCircle2 class="w-4 h-4 text-sunshine" />
                                <span>Verifikasi Laporan</span>
                            </button>
                            <span
                                v-else
                                class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200"
                            >
                                Selesai Diverifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Metrics & Evidence Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Pelaksana</span>
                            <span class="font-bold text-gray-800">{{ rep.submitter?.profile?.display_name ?? rep.submitter?.name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Tanggal Aksi</span>
                            <span class="font-bold text-gray-800">{{ new Date(rep.activity_date).toLocaleDateString('id-ID') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Peserta Diklaim / Terverifikasi</span>
                            <span class="font-bold text-gray-800">
                                {{ rep.claimed_participants }} orang / 
                                <span class="text-teal font-black">{{ rep.verified_participants ?? '-' }} terverifikasi</span>
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold uppercase">Tautan Bukti Dokumentasi</span>
                            <a
                                v-if="rep.evidence_path"
                                :href="rep.evidence_path"
                                target="_blank"
                                class="text-teal hover:underline font-bold flex items-center gap-1 truncate"
                            >
                                <ExternalLink class="w-3.5 h-3.5 shrink-0" />
                                <span class="truncate">Buka Berkas Bukti</span>
                            </a>
                            <span v-else class="text-gray-400 italic">Tidak ada tautan</span>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <AppPagination :links="reports.links" />
            </div>

            <!-- Empty State -->
            <AppEmptyState
                v-else
                title="Tidak Ada Laporan Kegiatan"
                description="Belum ada laporan kegiatan advokasi yang diajukan oleh Duta GenRe."
                :icon="FileCheck2"
            />
        </div>

        <!-- Verification Modal -->
        <Modal :show="isVerifyModalOpen" @close="closeVerifyModal">
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal flex items-center justify-center shrink-0">
                        <FileCheck2 class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900">
                            Verifikasi Laporan & Dampak Kegiatan
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ selectedReport?.campaign?.title }}
                        </p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl text-xs text-amber-800 space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <AlertCircle class="w-4 h-4 text-amber-600" />
                        <span>Prinsip Keandalan Data Impact Map</span>
                    </div>
                    <p class="text-[11px] text-amber-700 leading-relaxed">
                        Hanya jumlah peserta yang didukung bukti sahih yang dimasukkan ke statistik nasional. Klaim berlebihan tanpa bukti dapat disesuaikan.
                    </p>
                </div>

                <!-- Verified Participants -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">
                        Jumlah Peserta Terverifikasi (Klaim: {{ selectedReport?.claimed_participants }} orang):
                    </label>
                    <input
                        type="number"
                        v-model.number="verifyForm.verified_participants"
                        min="0"
                        class="w-full text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                        required
                    />
                    <p v-if="verifyForm.errors.verified_participants" class="text-xs text-rose-600 font-medium">
                        {{ verifyForm.errors.verified_participants }}
                    </p>
                </div>

                <!-- Verification Status Toggle -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">Keputusan Hasil Verifikasi:</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-800 cursor-pointer">
                            <input
                                type="radio"
                                :value="true"
                                v-model="verifyForm.verified"
                                class="text-forest focus:ring-forest"
                            />
                            <span>Diverifikasi Sah (Masuk Impact Map)</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-800 cursor-pointer">
                            <input
                                type="radio"
                                :value="false"
                                v-model="verifyForm.verified"
                                class="text-rose-600 focus:ring-rose-500"
                            />
                            <span>Tolak Verifikasi</span>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700">Catatan Verifikator (Opsional):</label>
                    <textarea
                        v-model="verifyForm.notes"
                        rows="3"
                        class="w-full text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                        placeholder="Contoh: Bukti foto dan daftar hadir sesuai, terkonfirmasi 45 peserta aktif..."
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeVerifyModal" :disabled="verifyForm.processing">
                        Batal
                    </SecondaryButton>
                    <button
                        @click="submitVerification"
                        :disabled="verifyForm.processing"
                        class="px-5 py-2 rounded-xl bg-forest hover:bg-forest-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50"
                    >
                        {{ verifyForm.processing ? 'Menyimpan...' : 'Simpan Verifikasi' }}
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
