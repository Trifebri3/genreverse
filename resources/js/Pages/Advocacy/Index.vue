<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Megaphone, 
    PlusCircle, 
    MapPin, 
    CheckCircle2,
    ArrowRight
} from 'lucide-vue-next';

const props = defineProps<{
    campaigns: any;
    regions: any[];
    selectedCategory?: string;
    selectedRegion?: string;
}>();

const page = usePage();
const auth = page.props.auth as any;

function filter(category?: string, region?: string) {
    router.get(route('campaigns.index'), {
        category: category || props.selectedCategory || undefined,
        region: region || props.selectedRegion || undefined,
    }, { preserveState: true });
}
</script>

<template>
    <PublicLayout>
        <Head title="AKSARA BERDAYA - Ruang Advokasi Digital GENREVERSE" />

        <!-- Hero Header with Playful Blue & Photo -->
        <section class="bg-gradient-to-b from-blue-100/70 via-sky-50 to-[#F0F7FF] pt-10 pb-14 border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    <div class="md:col-span-8 space-y-4">
                        <AppBadge variant="teal">📢 Ruang Advokasi Digital GENREVERSE</AppBadge>
                        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            AKSARA BERDAYA ✨
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                            Ruang advokasi digital GENREVERSE yang menjadi wadah bagi remaja untuk menyuarakan aspirasi, memperluas wawasan, dan mengembangkan potensi diri. Melalui edukasi, informasi, serta penguatan resiliensi, AKSARA BERDAYA mendorong remaja menjadi generasi yang kritis, tangguh, peduli, dan mampu menciptakan perubahan positif.
                        </p>

                        <div v-if="auth?.user?.is_ambassador || auth?.user?.is_admin" class="pt-2">
                            <Link
                                :href="route('campaigns.create')"
                                class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-sm transition shadow-lg"
                            >
                                <PlusCircle class="w-5 h-5 text-amber-300" />
                                <span>+ Gagas Aksi Aksara Berdaya</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Side Photo Badge -->
                    <div class="md:col-span-4 hidden md:flex justify-end">
                        <div class="relative rounded-3xl overflow-hidden border-4 border-white shadow-xl max-w-xs -rotate-2 hover:rotate-0 transition-transform">
                            <img
                                src="/images/advocacy_cover.jpg"
                                alt="Aksi Advokasi Duta GenRe"
                                class="w-full h-48 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent flex items-end p-3.5">
                                <span class="text-[11px] font-black text-amber-300">🎉 Aksi Nyata Duta Muda</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Campaigns Content -->
        <section class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filter Strip -->
            <div class="flex flex-wrap items-center gap-2 p-3 rounded-2xl bg-white border border-blue-100 shadow-sm">
                <button
                    @click="filter('', selectedRegion)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-xs"
                    :class="!selectedCategory ? 'bg-blue-600 text-white' : 'bg-blue-50 text-slate-600 hover:bg-blue-100'"
                >
                    Semua Isu
                </button>
                <button
                    @click="filter('kesehatan_reproduksi', selectedRegion)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-xs"
                    :class="selectedCategory === 'kesehatan_reproduksi' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-slate-600 hover:bg-blue-100'"
                >
                    Cegah Anemia & Stunting
                </button>
                <button
                    @click="filter('pendewasaan_usia_perkawinan', selectedRegion)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-xs"
                    :class="selectedCategory === 'pendewasaan_usia_perkawinan' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-slate-600 hover:bg-blue-100'"
                >
                    PUP & Nikah Matang
                </button>
                <button
                    @click="filter('life_skills', selectedRegion)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap shadow-xs"
                    :class="selectedCategory === 'life_skills' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-slate-600 hover:bg-blue-100'"
                >
                    Relasi Sehat & Batasan Diri
                </button>
            </div>

            <!-- Campaign Cards Grid with Photo Headers -->
            <div v-if="campaigns.data.length === 0" class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-blue-200 p-8 space-y-2">
                <Megaphone class="w-12 h-12 text-blue-300 mx-auto" />
                <h3 class="text-base font-bold text-slate-800">Belum ada kampanye untuk filter ini</h3>
                <p class="text-xs text-slate-500">Coba pilih kategori isu atau wilayah yang lain.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="camp in campaigns.data"
                    :key="camp.id"
                    class="bg-white rounded-3xl border-2 border-blue-100 overflow-hidden flex flex-col justify-between hover:shadow-2xl hover:border-blue-300 transition-all duration-300 group"
                >
                    <div>
                        <!-- Real Photo Header -->
                        <div class="relative h-44 overflow-hidden bg-blue-100">
                            <img
                                src="/images/advocacy_cover.jpg"
                                :alt="camp.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                            <span class="absolute top-3 left-3 px-2.5 py-1 bg-white/95 backdrop-blur-sm rounded-lg text-[10px] font-black text-blue-700 shadow-xs uppercase">
                                {{ camp.issue_category.replace(/_/g, ' ') }}
                            </span>
                            <span class="absolute bottom-3 left-3 text-xs text-white font-bold flex items-center gap-1 drop-shadow">
                                <MapPin class="w-3.5 h-3.5 text-amber-400" />
                                <span>{{ camp.region?.name ?? 'Nasional' }}</span>
                            </span>
                        </div>

                        <div class="p-6 space-y-3">
                            <h2 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ camp.title }}
                            </h2>

                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ camp.problem_statement }}
                            </p>

                            <!-- Verified impact pill -->
                            <div class="p-3 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-between text-xs font-semibold text-blue-900">
                                <span class="flex items-center gap-1.5 text-blue-700 font-bold">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-500" />
                                    <span>Peserta Terverifikasi</span>
                                </span>
                                <strong class="font-black text-blue-600">
                                    {{ camp.reports?.reduce((sum: number, r: any) => sum + (r.verified_participants || 0), 0) }} Remaja
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
                        <div class="text-[11px] text-slate-500 font-medium truncate max-w-[150px]">
                            Oleh: <strong class="text-slate-800">{{ camp.creator?.profile?.display_name ?? camp.creator?.name }}</strong>
                        </div>

                        <Link
                            :href="route('campaigns.show', camp.slug)"
                            class="px-4 py-2 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white transition flex items-center gap-1 shadow-sm"
                        >
                            <span>Dukung</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="campaigns.links?.length > 3" class="flex justify-center gap-2 pt-6">
                <Link
                    v-for="(link, i) in campaigns.links"
                    :key="i"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
                    :class="[
                        link.active ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-blue-50 border border-blue-200',
                        !link.url ? 'opacity-40 pointer-events-none' : ''
                    ]"
                />
            </div>
        </section>
    </PublicLayout>
</template>
