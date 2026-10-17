<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    BookOpen, 
    Search, 
    Clock, 
    ChevronRight, 
    Sparkles
} from 'lucide-vue-next';

const props = defineProps<{
    modules: any;
    categories: any[];
    selectedCategory?: string;
    searchQuery?: string;
    completedLessonIds: number[];
}>();

const search = ref(props.searchQuery || '');

function handleSearch() {
    router.get(route('academy.index'), {
        category: props.selectedCategory,
        q: search.value,
    }, { preserveState: true });
}

function filterCategory(slug?: string) {
    router.get(route('academy.index'), {
        category: slug,
        q: search.value || undefined,
    }, { preserveState: true });
}
</script>

<template>
    <PublicLayout>
        <Head title="GenRe Academy - Pusat Microlearning Remaja Terverifikasi" />

        <!-- Header Hero with Cute Playful Blue & Photo -->
        <section class="bg-gradient-to-b from-blue-100/70 via-sky-50 to-[#F0F7FF] pt-10 pb-14 border-b border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    <div class="md:col-span-8 space-y-4">
                        <AppBadge variant="forest">📚 Kurikulum Edukasi Berencana</AppBadge>
                        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            GenRe Academy ✨
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                            Belajar seru dan ilmiah tanpa tabu! Temukan jawaban seputar perubahan pubertas, gizi remaja bebas anemia, pendewasaan usia perkawinan (PUP), serta trik asertif menghadapi tekanan teman sebaya.
                        </p>

                        <!-- Search Input -->
                        <form @submit.prevent="handleSearch" class="pt-2 flex items-center gap-2 max-w-md">
                            <div class="relative flex-1">
                                <Search class="w-4 h-4 text-blue-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Cari materi atau topik seru..."
                                    class="w-full pl-10 pr-4 py-3 rounded-2xl border-2 border-blue-200 bg-white text-xs sm:text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-sm"
                                />
                            </div>
                            <button
                                type="submit"
                                class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs sm:text-sm transition shadow-md"
                            >
                                Cari
                            </button>
                        </form>
                    </div>

                    <!-- Side Photo Badge -->
                    <div class="md:col-span-4 hidden md:flex justify-end">
                        <div class="relative rounded-3xl overflow-hidden border-4 border-white shadow-xl max-w-xs rotate-2 hover:rotate-0 transition-transform">
                            <img
                                src="/images/academy_cover.jpg"
                                alt="Belajar di GenRe Academy"
                                class="w-full h-48 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent flex items-end p-3.5">
                                <span class="text-[11px] font-black text-amber-300">💡 +20 XP Tiap Modul Selesai!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories & Modules Listing -->
        <section class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Category Pills Bar -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <button
                    @click="filterCategory(undefined)"
                    class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all shadow-xs"
                    :class="[
                        !selectedCategory
                            ? 'bg-blue-600 text-white shadow-sm'
                            : 'bg-white text-slate-700 hover:bg-blue-50 border border-blue-200'
                    ]"
                >
                    Semua Topik
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="filterCategory(cat.slug)"
                    class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all shadow-xs"
                    :class="[
                        selectedCategory === cat.slug
                            ? 'bg-blue-600 text-white shadow-sm'
                            : 'bg-white text-slate-700 hover:bg-blue-50 border border-blue-200'
                    ]"
                >
                    {{ cat.name }}
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="modules.data.length === 0" class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-blue-200 p-8 space-y-3">
                <BookOpen class="w-12 h-12 text-blue-300 mx-auto" />
                <h3 class="text-lg font-bold text-slate-800">Tidak ada modul ditemukan</h3>
                <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau pilih kategori yang lain.</p>
                <button @click="filterCategory(undefined)" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold">
                    Reset Filter
                </button>
            </div>

            <!-- Modules Grid with Real Photos -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(mod, i) in modules.data"
                    :key="mod.id"
                    class="bg-white rounded-3xl border-2 border-blue-100 overflow-hidden flex flex-col justify-between hover:shadow-xl hover:border-blue-300 transition-all duration-300 group"
                >
                    <div>
                        <!-- Photo Header -->
                        <div class="relative h-44 overflow-hidden bg-blue-100">
                            <img
                                :src="mod.cover_path && !mod.cover_path.includes('/images/modules/') ? mod.cover_path : (i % 3 === 0 ? '/images/topic_kespro.jpg' : (i % 3 === 1 ? '/images/academy_cover.jpg' : '/images/topic_mentalhealth.jpg'))"
                                :alt="mod.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <span class="absolute top-3 left-3 px-2.5 py-1 bg-white/95 backdrop-blur-sm rounded-lg text-[10px] font-black text-blue-700 shadow-xs">
                                {{ mod.category?.name ?? 'Edukasi' }}
                            </span>
                            <span class="absolute bottom-3 right-3 px-2 py-0.5 bg-slate-900/80 text-white rounded-md text-[10px] font-bold">
                                ⏱️ {{ mod.estimated_minutes }} Menit
                            </span>
                        </div>

                        <div class="p-6">
                            <h2 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                                {{ mod.title }}
                            </h2>

                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ mod.summary }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider px-2 py-0.5 rounded-md bg-blue-50 border border-blue-100">
                                {{ mod.difficulty }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium">{{ mod.lessons?.length ?? 0 }} Sesi Belajar</span>
                        </div>

                        <Link
                            :href="route('academy.show', mod.slug)"
                            class="px-4 py-2 rounded-xl text-xs font-black bg-amber-400 text-slate-900 hover:bg-amber-300 transition flex items-center gap-1 shadow-sm"
                        >
                            <span>Pelajari</span>
                            <ChevronRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Pagination Links -->
            <div v-if="modules.links?.length > 3" class="flex justify-center gap-2 pt-6">
                <Link
                    v-for="(link, i) in modules.links"
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
