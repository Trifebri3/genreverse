<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search, 
    DownloadCloud, 
    FolderArchive, 
    FileText, 
    CheckCircle2, 
    Sparkles, 
    Info,
    Calendar,
    ArrowDownToLine
} from 'lucide-vue-next';

const props = defineProps<{
    documents: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
    };
    categories: string[];
    totalDownloads: number;
    filters: {
        search?: string;
        category?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || 'all');

const handleSearch = () => {
    router.get(
        route('documents.index'),
        { search: search.value, category: selectedCategory.value },
        { preserveState: true, replace: true }
    );
};

const filterByCategory = (cat: string) => {
    selectedCategory.value = cat;
    handleSearch();
};

const formatSize = (bytes: number) => {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' B';
};
</script>

<template>
    <PublicLayout>
        <Head title="Pusat Unduhan Dokumen & Laporan - GENREVERSE" />

        <div class="space-y-12 pb-16">
            <!-- Hero Banner -->
            <section class="relative bg-gradient-to-r from-teal to-forest text-white rounded-3xl p-8 sm:p-12 overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-sunshine text-forest">
                        <FolderArchive class="w-3.5 h-3.5" />
                        <span>E-Library & Laporan Resmi</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-white">
                        Pusat Unduhan Dokumen, Panduan & Laporan GenRe
                    </h1>
                    <p class="text-sm text-teal-100 leading-relaxed">
                        Akses modul edukasi terverifikasi, laporan capaian tahunan, petunjuk teknis (Juknis) kegiatan, regulasi BKKBN, dan template materi advokasi siap pakai.
                    </p>

                    <!-- Search Input -->
                    <div class="pt-2 max-w-md">
                        <div class="relative">
                            <Search class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="search"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Cari judul dokumen atau laporan..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sunshine"
                            />
                        </div>
                    </div>
                </div>

                <!-- Abstract Decorative Shapes -->
                <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            </section>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest-50 text-forest flex items-center justify-center font-bold">
                        <FileText class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900">{{ documents.total }}</div>
                        <div class="text-xs text-gray-400">Total Berkas Tersedia</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal flex items-center justify-center font-bold">
                        <DownloadCloud class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900">{{ totalDownloads }}x</div>
                        <div class="text-xs text-gray-400">Total Diunduh Remaja</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm col-span-2 sm:col-span-1 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <CheckCircle2 class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="text-sm font-black text-gray-900">PDF, DOCX, XLSX</div>
                        <div class="text-xs text-gray-400">Format Resmi & Bebas Akses</div>
                    </div>
                </div>
            </div>

            <!-- Category Pills Filter -->
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

            <!-- Documents Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div 
                    v-for="doc in documents.data" 
                    :key="doc.id" 
                    class="bg-white rounded-2xl border border-gray-200/90 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4 group"
                >
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-teal-50 text-teal-800 border border-teal-200">
                                {{ doc.category }}
                            </span>
                            <span class="text-[11px] font-bold text-gray-400 uppercase">
                                {{ doc.file_type || 'PDF' }} • {{ formatSize(doc.file_size) }}
                            </span>
                        </div>

                        <h3 class="text-base font-black text-gray-900 group-hover:text-forest transition leading-snug">
                            {{ doc.title }}
                        </h3>

                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ doc.description || 'Berkas dokumen resmi panduan kegiatan Duta GenRe dan materi advokasi pemuda.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400 flex items-center gap-1">
                            <DownloadCloud class="w-3.5 h-3.5" />
                            <span>Diunduh {{ doc.downloads_count }} kali</span>
                        </span>

                        <a 
                            :href="route('documents.download', doc.id)" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition"
                        >
                            <ArrowDownToLine class="w-4 h-4" />
                            <span>Unduh Sekarang</span>
                        </a>
                    </div>
                </div>
            </div>

            <div v-if="documents.data.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                Tidak ada dokumen yang sesuai dengan kata kunci pencarian Anda.
            </div>
        </div>
    </PublicLayout>
</template>
