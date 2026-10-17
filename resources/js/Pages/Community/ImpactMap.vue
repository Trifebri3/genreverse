<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    MapPin, 
    Users, 
    CheckCircle2, 
    ShieldCheck, 
    Calendar, 
    Megaphone,
    Award
} from 'lucide-vue-next';

const props = defineProps<{
    provinces: any[];
    selectedProvince: string;
    verifiedReports: any[];
    stats: {
        total_verified_participants: number;
        total_published_campaigns: number;
        total_published_events: number;
        total_provinces: number;
    };
}>();

function selectProvince(code: string) {
    router.get(route('impact_map'), {
        province: code,
    }, { preserveState: true });
}
</script>

<template>
    <PublicLayout>
        <Head title="Peta Dampak Nasional - GENREVERSE" />

        <div class="py-12 bg-gradient-to-b from-teal-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <AppBadge variant="teal">Transparansi & Dampak Nyata</AppBadge>
                    <h1 class="text-3xl sm:text-5xl font-black text-forest tracking-tight">
                        Peta Dampak GenRe Indonesia
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Data agregat keterlibatan remaja dan kegiatan advokasi yang telah melewati verifikasi resmi. Seluruh statistik didasarkan pada laporan kegiatan yang tervalidasi.
                    </p>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-white rounded-3xl border border-forest/10 shadow-md">
                    <div class="text-center p-3 border-r border-gray-100 last:border-none">
                        <div class="text-2xl sm:text-3xl font-black text-forest">{{ stats.total_verified_participants.toLocaleString('id-ID') }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Remaja Terdampak Terverifikasi</div>
                    </div>
                    <div class="text-center p-3 border-r border-gray-100 last:border-none">
                        <div class="text-2xl sm:text-3xl font-black text-teal">{{ stats.total_published_campaigns }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Kampanye Advokasi Aktif</div>
                    </div>
                    <div class="text-center p-3 border-r border-gray-100 last:border-none">
                        <div class="text-2xl sm:text-3xl font-black text-sunshine-600">{{ stats.total_published_events }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Kegiatan Komunitas</div>
                    </div>
                    <div class="text-center p-3">
                        <div class="text-2xl sm:text-3xl font-black text-emerald-600">{{ stats.total_provinces }}</div>
                        <div class="text-xs font-semibold text-gray-500 mt-1">Provinsi Terhubung</div>
                    </div>
                </div>

                <!-- Interactive Region Selector -->
                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg font-black text-forest">Eksplorasi Sebaran Daerah</h2>
                            <p class="text-xs text-gray-500">Pilih provinsi untuk menyaring capaian dampak lokal.</p>
                        </div>
                        <span class="text-xs font-bold text-teal flex items-center gap-1.5">
                            <ShieldCheck class="w-4 h-4 text-emerald-600" />
                            <span>Privasi Peserta Aman</span>
                        </span>
                    </div>

                    <!-- Province Pills -->
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            @click="selectProvince('ALL')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition"
                            :class="selectedProvince === 'ALL' ? 'bg-forest text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        >
                            Seluruh Indonesia (Nasional)
                        </button>
                        <button
                            v-for="prov in provinces"
                            :key="prov.id"
                            type="button"
                            @click="selectProvince(prov.code)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                            :class="selectedProvince === prov.code ? 'bg-forest text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        >
                            <span>{{ prov.name }}</span>
                            <span v-if="prov.campaigns_count > 0" class="w-1.5 h-1.5 rounded-full bg-sunshine"></span>
                        </button>
                    </div>
                </div>

                <!-- Verified Activities Feed -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold text-forest flex items-center gap-2">
                            <CheckCircle2 class="w-5 h-5 text-emerald-600" />
                            <span>Catatan Kegiatan & Dampak Terverifikasi</span>
                        </h2>
                        <span class="text-xs text-gray-500 font-semibold">{{ verifiedReports.length }} Laporan</span>
                    </div>

                    <div v-if="verifiedReports.length === 0" class="text-center py-12 bg-white rounded-3xl border border-forest/10 p-6 space-y-2">
                        <MapPin class="w-10 h-10 text-gray-300 mx-auto" />
                        <h4 class="text-sm font-bold text-gray-700">Belum ada laporan terverifikasi untuk wilayah ini</h4>
                        <p class="text-xs text-gray-500">Duta GenRe di daerah ini sedang mempersiapkan dan melaksanakan aksi sosial.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            v-for="rep in verifiedReports"
                            :key="rep.id"
                            class="bg-white rounded-3xl border border-emerald-200 p-6 shadow-sm space-y-4 flex flex-col justify-between"
                        >
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                        {{ rep.verified_participants }} Peserta Terverifikasi
                                    </span>
                                    <span class="text-gray-400 font-medium">
                                        {{ new Date(rep.activity_date).toLocaleDateString('id-ID', { dateStyle: 'medium' }) }}
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-forest">
                                    {{ rep.campaign?.title }}
                                </h3>

                                <div class="text-xs font-semibold text-gray-500 flex items-center gap-1">
                                    <MapPin class="w-3.5 h-3.5 text-gray-400" />
                                    <span>{{ rep.campaign?.region?.name }}</span>
                                </div>

                                <p class="text-xs text-gray-600 leading-relaxed pt-1">
                                    {{ rep.summary }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-gray-100 text-[11px] text-gray-400">
                                Dilaporkan oleh: <strong class="text-gray-700">{{ rep.campaign?.creator?.profile?.display_name ?? rep.campaign?.creator?.name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
