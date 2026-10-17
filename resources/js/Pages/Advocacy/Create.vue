<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { Megaphone, ArrowLeft, Send } from 'lucide-vue-next';

const props = defineProps<{
    regions: any[];
}>();

const form = useForm({
    title: '',
    region_id: props.regions[0]?.id || 1,
    issue_category: 'kesehatan_reproduksi',
    problem_statement: '',
    objective: '',
    target_audience: '',
    call_to_action: '',
    planned_start: '',
    planned_end: '',
});

function submit() {
    form.post(route('campaigns.store'));
}
</script>

<template>
    <PublicLayout>
        <Head title="Gagas Gerakan Baru - AKSARA BERDAYA" />

        <div class="py-12 bg-gradient-to-b from-blue-50 to-[#F0F7FF] min-h-screen">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <Link :href="route('campaigns.index')" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 transition">
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke AKSARA BERDAYA</span>
                </Link>

                <div class="bg-white rounded-3xl border border-blue-100 p-6 sm:p-10 shadow-sm space-y-6">
                    <div class="space-y-1">
                        <AppBadge variant="teal">Aksara Berdaya Generator</AppBadge>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                            Gagas Gerakan Advokasi Digital
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600">
                            Susun rencana gerakan advokasi dan edukasi teman sebaya untuk menciptakan perubahan positif di wilayahmu.
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5 text-sm">
                        <!-- Judul -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Judul Kampanye</label>
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                placeholder="Contoh: Gerakan Sarapan Berprotein & Minum TTD Remaja Putri SMAN 1"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                            />
                            <div v-if="form.errors.title" class="text-xs text-rose-600 mt-1">{{ form.errors.title }}</div>
                        </div>

                        <!-- Kategori Isu & Wilayah -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Isu Prioritas</label>
                                <select
                                    v-model="form.issue_category"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                >
                                    <option value="kesehatan_reproduksi">Kesehatan Reproduksi & Gizi (Cegah Stunting)</option>
                                    <option value="pendewasaan_usia_perkawinan">Pendewasaan Usia Perkawinan (PUP)</option>
                                    <option value="life_skills">Relasi Sehat & Batasan Diri</option>
                                    <option value="mental_health">Kesehatan Mental Remaja</option>
                                    <option value="peer_pressure">Anti Narkoba & Tawuran (Peer Pressure)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Wilayah Sasaran</label>
                                <select
                                    v-model="form.region_id"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                >
                                    <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Latar Belakang -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Latar Belakang Masalah</label>
                            <textarea
                                v-model="form.problem_statement"
                                rows="3"
                                required
                                placeholder="Apa masalah nyata di lingkunganmu yang ingin diselesaikan melalui kampanye ini?"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                            ></textarea>
                            <div v-if="form.errors.problem_statement" class="text-xs text-rose-600 mt-1">{{ form.errors.problem_statement }}</div>
                        </div>

                        <!-- Tujuan -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tujuan Kampanye</label>
                            <textarea
                                v-model="form.objective"
                                rows="2"
                                required
                                placeholder="Hasil spesifik yang ingin dicapai (misal: mengedukasi 200 remaja tentang bahaya anemia)..."
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                            ></textarea>
                        </div>

                        <!-- Target Audiens & Call To Action -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Target Audiens</label>
                                <input
                                    v-model="form.target_audience"
                                    type="text"
                                    required
                                    placeholder="Contoh: Siswa-siswi kelas 10 dan 11 SMA"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ajakan Bertindak (Call to Action)</label>
                                <input
                                    v-model="form.call_to_action"
                                    type="text"
                                    required
                                    placeholder="Contoh: Ayo minum TTD setiap Jumat pagi!"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3">
                            <Link :href="route('campaigns.index')" class="px-5 py-2.5 rounded-xl border border-gray-200 font-bold text-xs text-gray-600 hover:bg-gray-50">
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-7 py-3 rounded-xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow flex items-center gap-2 disabled:opacity-50"
                            >
                                <Send class="w-4 h-4 text-sunshine" />
                                <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Draft Kampanye' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
