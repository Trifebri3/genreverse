<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { 
    Megaphone, 
    MapPin, 
    CheckCircle2, 
    ArrowLeft, 
    Calendar, 
    Users, 
    FileText, 
    PlusCircle,
    Send,
    Award
} from 'lucide-vue-next';

const props = defineProps<{
    campaign: any;
    canManage: boolean;
}>();

const reportModalOpen = ref(false);

const reportForm = useForm({
    activity_date: new Date().toISOString().substring(0, 10),
    summary: '',
    claimed_participants: 50,
    evidence_path: '',
});

function submitReport() {
    reportForm.post(route('campaigns.submit_report', props.campaign.id), {
        onSuccess: () => {
            reportModalOpen.value = false;
            reportForm.reset();
        }
    });
}

const reviewForm = useForm({});
function submitForReview() {
    reviewForm.post(route('campaigns.submit_review', props.campaign.id));
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${campaign.title} - AKSARA BERDAYA`" />

        <div class="py-10 bg-gradient-to-b from-blue-50 to-[#F0F7FF] min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Back Link -->
                <Link :href="route('campaigns.index')" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 transition">
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke AKSARA BERDAYA</span>
                </Link>

                <!-- Campaign Header Card -->
                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-sm space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <AppBadge variant="teal">{{ campaign.issue_category.replace(/_/g, ' ') }}</AppBadge>
                            <StatusBadge :status="campaign.status" />
                        </div>
                        <span class="text-xs font-semibold text-gray-500 flex items-center gap-1">
                            <MapPin class="w-4 h-4 text-gray-400" />
                            <span>{{ campaign.region?.name ?? 'Nasional' }}</span>
                        </span>
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-2xl sm:text-4xl font-black text-forest tracking-tight">
                            {{ campaign.title }}
                        </h1>
                        <div class="text-xs text-gray-500">
                            Diinisiasi oleh: <strong class="text-gray-800">{{ campaign.creator?.profile?.display_name ?? campaign.creator?.name }}</strong>
                        </div>
                    </div>

                    <!-- Creator Actions Strip -->
                    <div v-if="canManage" class="p-4 rounded-2xl bg-forest-50 border border-forest-100 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs font-bold text-forest">
                            Panel Penggagas Kampanye
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                v-if="campaign.status === 'draft'"
                                @click="submitForReview"
                                :disabled="reviewForm.processing"
                                class="px-4 py-2 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition shadow flex items-center gap-1.5"
                            >
                                <Send class="w-3.5 h-3.5" />
                                <span>Ajukan Review Moderator</span>
                            </button>
                            <button
                                @click="reportModalOpen = true"
                                class="px-4 py-2 rounded-xl bg-sunshine text-forest text-xs font-black hover:bg-sunshine-300 transition shadow flex items-center gap-1.5"
                            >
                                <PlusCircle class="w-3.5 h-3.5" />
                                <span>Kirim Laporan Kegiatan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Structured Sections -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 text-sm">
                        <div class="space-y-2">
                            <h3 class="font-extrabold text-forest uppercase tracking-wider text-xs">Latar Belakang & Masalah</h3>
                            <p class="text-gray-600 leading-relaxed">{{ campaign.problem_statement }}</p>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-extrabold text-forest uppercase tracking-wider text-xs">Tujuan Kampanye</h3>
                            <p class="text-gray-600 leading-relaxed">{{ campaign.objective }}</p>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-extrabold text-forest uppercase tracking-wider text-xs">Target Audiens</h3>
                            <p class="text-gray-600 leading-relaxed">{{ campaign.target_audience }}</p>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-extrabold text-forest uppercase tracking-wider text-xs">Ajakan Bertindak (Call to Action)</h3>
                            <p class="text-gray-900 font-bold leading-relaxed bg-sunshine-50 p-3 rounded-xl border border-sunshine-200">
                                📢 {{ campaign.call_to_action }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Verified Impact Reports Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold text-forest flex items-center gap-2">
                            <CheckCircle2 class="w-5 h-5 text-emerald-600" />
                            <span>Laporan Kegiatan Terverifikasi</span>
                        </h2>
                        <span class="text-xs font-semibold text-gray-500">{{ campaign.reports?.length ?? 0 }} Laporan Terbit</span>
                    </div>

                    <div v-if="campaign.reports?.length === 0" class="text-center py-10 bg-white rounded-3xl border border-forest/10 p-6 space-y-2">
                        <FileText class="w-10 h-10 text-gray-300 mx-auto" />
                        <div class="text-sm font-bold text-gray-700">Belum ada laporan kegiatan yang diverifikasi</div>
                        <p class="text-xs text-gray-500">Duta penggagas akan mengunggah dokumentasi dan jumlah peserta setelah aksi terlaksana.</p>
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="rep in campaign.reports"
                            :key="rep.id"
                            class="bg-white rounded-2xl border border-emerald-200 p-6 shadow-sm space-y-3"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                    {{ rep.verified_participants }} Remaja Peserta Terverifikasi
                                </span>
                                <span class="text-xs text-gray-500">{{ new Date(rep.activity_date).toLocaleDateString('id-ID', { dateStyle: 'long' }) }}</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ rep.summary }}</p>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT REPORT MODAL -->
                <div v-if="reportModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full text-gray-900 shadow-2xl border border-forest/20 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="text-lg font-black text-forest">Kirim Laporan Hasil Kegiatan</h3>
                            <button @click="reportModalOpen = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                        </div>

                        <form @submit.prevent="submitReport" class="space-y-4 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pelaksanaan Kegiatan</label>
                                <input
                                    v-model="reportForm.activity_date"
                                    type="date"
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Peserta Remaja (Klaim)</label>
                                <input
                                    v-model="reportForm.claimed_participants"
                                    type="number"
                                    min="1"
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ringkasan Kegiatan & Hasil</label>
                                <textarea
                                    v-model="reportForm.summary"
                                    rows="4"
                                    required
                                    placeholder="Ceritakan jalannya kegiatan, respons peserta, dan materi yang disampaikan..."
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-3">
                                <button
                                    type="button"
                                    @click="reportModalOpen = false"
                                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="reportForm.processing"
                                    class="px-5 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition shadow disabled:opacity-50"
                                >
                                    {{ reportForm.processing ? 'Mengirim...' : 'Kirim Laporan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
