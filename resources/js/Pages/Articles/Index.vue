<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search, 
    Calendar, 
    Eye, 
    ArrowRight, 
    Newspaper, 
    Sparkles, 
    TrendingUp,
    Folder
} from 'lucide-vue-next';

const props = defineProps<{
    articles: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
    };
    featured?: any;
    popularArticles?: any[];
    categories: string[];
    filters: {
        search?: string;
        category?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || 'all');

const handleSearch = () => {
    router.get(
        route('articles.index'),
        { search: search.value, category: selectedCategory.value },
        { preserveState: true, replace: true }
    );
};

const filterByCategory = (cat: string) => {
    selectedCategory.value = cat;
    handleSearch();
};
</script>

<template>
    <PublicLayout>
        <Head title="Kabar & Publikasi GenRe - GENREVERSE" />

        <div class="space-y-12 pb-16">
            <!-- Hero Banner -->
            <section class="relative bg-gradient-to-r from-forest to-forest-700 text-white rounded-3xl p-8 sm:p-12 overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-sunshine text-forest">
                        <Sparkles class="w-3.5 h-3.5" />
                        <span>Kabar GenRe Terkini</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-white">
                        Warta, Inspirasi & Edukasi Remaja Indonesia
                    </h1>
                    <p class="text-sm text-forest-100 leading-relaxed">
                        Ikuti perkembangan inisiatif nasional, liputan aksi advokasi Duta GenRe, tips kecakapan hidup, dan kisah inspiratif pemuda berencana dari Sabang sampai Merauke.
                    </p>

                    <!-- Search Input -->
                    <div class="pt-2 max-w-md">
                        <div class="relative">
                            <Search class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="search"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Cari topik artikel atau kegiatan..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sunshine"
                            />
                        </div>
                    </div>
                </div>

                <!-- Abstract Glow -->
                <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-teal/20 blur-3xl pointer-events-none"></div>
            </section>

            <!-- Category Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <button
                    @click="filterByCategory('all')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 shadow-sm"
                    :class="selectedCategory === 'all' ? 'bg-forest text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                >
                    Semua Kategori
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat"
                    @click="filterByCategory(cat)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 shadow-sm"
                    :class="selectedCategory === cat ? 'bg-forest text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                >
                    {{ cat }}
                </button>
            </div>

            <!-- Featured Highlight Card (If on first page and not searching) -->
            <div v-if="featured && !filters.search && selectedCategory === 'all'" class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition grid grid-cols-1 md:grid-cols-2">
                <div class="h-64 md:h-auto bg-gray-100 overflow-hidden relative">
                    <img 
                        v-if="featured.cover_image" 
                        :src="featured.cover_image" 
                        :alt="featured.title" 
                        class="w-full h-full object-cover hover:scale-105 transition duration-500"
                    />
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-sunshine text-forest shadow">
                            Sorotan Utama
                        </span>
                    </div>
                </div>
                <div class="p-8 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ featured.category }}
                        </span>
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 leading-snug">
                            <Link :href="route('articles.show', featured.slug)" class="hover:text-forest transition">
                                {{ featured.title }}
                            </Link>
                        </h2>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ featured.excerpt }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <span>Oleh {{ featured.author_name || 'Humas GenRe' }}</span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <Eye class="w-3.5 h-3.5" />
                                <span>{{ featured.views_count }} dibaca</span>
                            </span>
                        </div>
                        <Link 
                            :href="route('articles.show', featured.slug)" 
                            class="inline-flex items-center gap-1 text-xs font-bold text-forest hover:gap-2 transition"
                        >
                            <span>Baca Selengkapnya</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Articles Grid & Popular Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Articles List -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div
                            v-for="art in articles.data"
                            :key="art.id"
                            class="bg-white rounded-2xl border border-gray-200/90 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group"
                        >
                            <!-- Cover Image -->
                            <div class="h-44 bg-gray-100 overflow-hidden relative">
                                <img
                                    v-if="art.cover_image"
                                    :src="art.cover_image"
                                    :alt="art.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                />
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-white/90 text-forest backdrop-blur-sm shadow-sm">
                                        {{ art.category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                                <div class="space-y-1.5">
                                    <h3 class="font-bold text-sm text-gray-900 group-hover:text-forest transition line-clamp-2 leading-snug">
                                        <Link :href="route('articles.show', art.slug)">
                                            {{ art.title }}
                                        </Link>
                                    </h3>
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                        {{ art.excerpt }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                                    <span class="truncate">{{ art.author_name || 'Admin' }}</span>
                                    <span class="flex items-center gap-1 shrink-0">
                                        <Eye class="w-3.5 h-3.5" />
                                        <span>{{ art.views_count }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="articles.data.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                        Tidak ada artikel yang cocok dengan pencarian Anda.
                    </div>
                </div>

                <!-- Popular & Trending Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <TrendingUp class="w-4 h-4 text-forest" />
                            <h3 class="text-sm font-black text-gray-900">Artikel Populer</h3>
                        </div>

                        <div class="space-y-4">
                            <div 
                                v-for="(pop, idx) in popularArticles" 
                                :key="pop.id" 
                                class="flex items-start gap-3 group"
                            >
                                <span class="text-xl font-black text-forest/30 group-hover:text-forest transition w-5 shrink-0">
                                    0{{ idx + 1 }}
                                </span>
                                <div class="space-y-1">
                                    <Link 
                                        :href="route('articles.show', pop.slug)" 
                                        class="text-xs font-bold text-gray-800 group-hover:text-forest line-clamp-2 leading-snug transition"
                                    >
                                        {{ pop.title }}
                                    </Link>
                                    <span class="text-[10px] text-gray-400 flex items-center gap-1">
                                        <Eye class="w-3 h-3" />
                                        <span>{{ pop.views_count }} pembaca</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Explore Card -->
                    <div class="bg-gradient-to-br from-forest-50 to-teal-50 rounded-2xl border border-forest-100 p-6 space-y-3">
                        <h3 class="text-sm font-black text-forest">Eksplorasi Advokasi</h3>
                        <p class="text-xs text-forest-700 leading-relaxed">
                            Ingin mempublikasikan kegiatan PIK-R atau aksi Duta GenRe daerahmu di portal nasional GENREVERSE? Hubungi pengurus wilayah Anda.
                        </p>
                        <Link 
                            :href="route('campaigns.index')" 
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-forest hover:underline"
                        >
                            <span>Buka Lab Advokasi</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
