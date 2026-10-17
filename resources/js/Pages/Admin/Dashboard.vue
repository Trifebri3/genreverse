<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    ArrowRight,
    Newspaper,
    FolderArchive,
    ImagePlus,
    HeartHandshake,
    Plus,
    Check,
    X,
    Star,
    Trash2,
    Calendar,
    DownloadCloud,
    ExternalLink,
    FileText,
    Sparkles,
    User
} from 'lucide-vue-next';

defineProps<{
    stats: {
        total_articles?: number;
        total_documents?: number;
        total_photos?: number;
        total_greetings?: number;
        pending_greetings?: number;
    };
    recentArticles?: any[];
    recentGreetings?: any[];
    recentDocuments?: any[];
    recentPhotos?: any[];
}>();

const updateGreetingStatus = (greet: any, status: string) => {
    router.post(route('admin.greetings.status', greet.id), { status }, { preserveScroll: true });
};

const formatSize = (bytes: number) => {
    if (!bytes) return '0 B';
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' B';
};
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard Publikasi - Admin GENREVERSE" />

        <div class="space-y-8">
            <!-- Header Banner with Direct Quick Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <Sparkles class="w-7 h-7 text-forest" />
                        <span>Pusat Publikasi & Konten Nasional</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Kelola berita, arsip dokumen, dokumentasi foto kegiatan, dan ucapan remaja se-Indonesia secara terpusat.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <Link
                        :href="route('admin.articles.index')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-700 shadow-sm transition"
                    >
                        <Plus class="w-3.5 h-3.5" />
                        <span>Tulis Berita</span>
                    </Link>
                    <Link
                        :href="route('admin.documents.index')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-teal text-white text-xs font-bold hover:bg-teal-700 shadow-sm transition"
                    >
                        <Plus class="w-3.5 h-3.5" />
                        <span>Unggah Dokumen</span>
                    </Link>
                    <Link
                        :href="route('admin.photos.index')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-sm transition"
                    >
                        <Plus class="w-3.5 h-3.5" />
                        <span>Upload Foto</span>
                    </Link>
                    <Link
                        :href="route('admin.greetings.index')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 shadow-sm transition"
                    >
                        <Plus class="w-3.5 h-3.5" />
                        <span>Tambah Ucapan</span>
                    </Link>
                </div>
            </div>

            <!-- Primary Metrics Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- Berita -->
                <Link
                    :href="route('admin.articles.index')"
                    class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm space-y-1 hover:border-forest hover:shadow-md transition group"
                >
                    <div class="flex items-center justify-between text-gray-400 group-hover:text-forest">
                        <span class="text-xs font-bold uppercase">Berita & Artikel</span>
                        <Newspaper class="w-4 h-4 text-forest" />
                    </div>
                    <div class="text-2xl font-black text-forest">{{ stats.total_articles ?? 0 }}</div>
                    <div class="text-[11px] text-gray-400">Total Publikasi Aktif</div>
                </Link>

                <!-- Dokumen -->
                <Link
                    :href="route('admin.documents.index')"
                    class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm space-y-1 hover:border-teal hover:shadow-md transition group"
                >
                    <div class="flex items-center justify-between text-gray-400 group-hover:text-teal">
                        <span class="text-xs font-bold uppercase">Dokumen & Laporan</span>
                        <FolderArchive class="w-4 h-4 text-teal" />
                    </div>
                    <div class="text-2xl font-black text-teal">{{ stats.total_documents ?? 0 }}</div>
                    <div class="text-[11px] text-gray-400">Arsip Unduhan Publik</div>
                </Link>

                <!-- Foto Galeri -->
                <Link
                    :href="route('admin.photos.index')"
                    class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm space-y-1 hover:border-blue-500 hover:shadow-md transition group"
                >
                    <div class="flex items-center justify-between text-gray-400 group-hover:text-blue-600">
                        <span class="text-xs font-bold uppercase">Galeri Foto</span>
                        <ImagePlus class="w-4 h-4 text-blue-600" />
                    </div>
                    <div class="text-2xl font-black text-blue-600">{{ stats.total_photos ?? 0 }}</div>
                    <div class="text-[11px] text-gray-400">Dokumentasi Kegiatan</div>
                </Link>

                <!-- Ucapan & Harapan -->
                <Link
                    :href="route('admin.greetings.index')"
                    class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-sm space-y-1 hover:border-rose-400 hover:shadow-md transition group"
                >
                    <div class="flex items-center justify-between text-gray-400 group-hover:text-rose-600">
                        <span class="text-xs font-bold uppercase">Ucapan & Testimoni</span>
                        <HeartHandshake class="w-4 h-4 text-rose-600" />
                    </div>
                    <div class="flex items-baseline gap-2">
                        <div class="text-2xl font-black text-rose-600">{{ stats.total_greetings ?? 0 }}</div>
                        <span v-if="(stats.pending_greetings ?? 0) > 0" class="text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">
                            {{ stats.pending_greetings }} pending
                        </span>
                    </div>
                    <div class="text-[11px] text-gray-400">Harapan Remaja Masuk</div>
                </Link>
            </div>

            <!-- Content Row 1: Articles & Greetings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Articles -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <Newspaper class="w-4 h-4 text-forest" />
                            <span>Publikasi Berita Terkini</span>
                        </h2>
                        <Link :href="route('admin.articles.index')" class="text-xs font-bold text-forest hover:underline flex items-center gap-1">
                            <span>Kelola Semua</span>
                            <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="art in recentArticles"
                            :key="art.id"
                            class="p-3 rounded-xl border border-gray-100 flex items-center justify-between gap-3 hover:bg-gray-50 transition"
                        >
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                    <img v-if="art.cover_image" :src="art.cover_image" :alt="art.title" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                        <Newspaper class="w-6 h-6" />
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs font-bold text-gray-900 truncate">{{ art.title }}</div>
                                    <div class="text-[10px] text-gray-400 flex items-center gap-2 mt-0.5">
                                        <span class="text-forest font-semibold">{{ art.category }}</span>
                                        <span>•</span>
                                        <span>{{ art.views_count }} dibaca</span>
                                    </div>
                                </div>
                            </div>
                            <span 
                                class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0"
                                :class="art.is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600'"
                            >
                                {{ art.is_published ? 'Tayang' : 'Draft' }}
                            </span>
                        </div>
                        <div v-if="!recentArticles || recentArticles.length === 0" class="text-center py-6 text-xs text-gray-400">
                            Belum ada berita. Klik "Tulis Berita" di atas untuk menambahkan.
                        </div>
                    </div>
                </div>

                <!-- Recent Greetings / Testimonials with quick moderation -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <HeartHandshake class="w-4 h-4 text-rose-600" />
                            <span>Ucapan & Harapan Remaja Masuk</span>
                        </h2>
                        <Link :href="route('admin.greetings.index')" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                            <span>Kelola Semua</span>
                            <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="greet in recentGreetings"
                            :key="greet.id"
                            class="p-3 rounded-xl border border-gray-100 flex items-start justify-between gap-3 hover:bg-gray-50 transition"
                        >
                            <div class="flex items-start gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-full bg-forest-50 overflow-hidden shrink-0 border border-forest-100">
                                    <img v-if="greet.photo_path" :src="greet.photo_path" :alt="greet.name" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-forest">
                                        <User class="w-5 h-5" />
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs font-bold text-gray-900 truncate">{{ greet.name }}</div>
                                    <p class="text-[11px] text-gray-500 line-clamp-1 italic mt-0.5">"{{ greet.message }}"</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span 
                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase shrink-0"
                                    :class="{
                                        'bg-emerald-50 text-emerald-700': greet.status === 'approved',
                                        'bg-amber-50 text-amber-700': greet.status === 'pending',
                                        'bg-rose-50 text-rose-700': greet.status === 'rejected'
                                    }"
                                >
                                    {{ greet.status }}
                                </span>
                                <button
                                    v-if="greet.status !== 'approved'"
                                    @click="updateGreetingStatus(greet, 'approved')"
                                    class="p-1 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition"
                                    title="Setujui"
                                >
                                    <Check class="w-3 h-3" />
                                </button>
                            </div>
                        </div>
                        <div v-if="!recentGreetings || recentGreetings.length === 0" class="text-center py-6 text-xs text-gray-400">
                            Belum ada ucapan masuk.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row 2: Documents & Photos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Documents -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <FolderArchive class="w-4 h-4 text-teal" />
                            <span>Arsip Dokumen & Laporan Terbaru</span>
                        </h2>
                        <Link :href="route('admin.documents.index')" class="text-xs font-bold text-teal hover:underline flex items-center gap-1">
                            <span>Kelola Dokumen</span>
                            <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="doc in recentDocuments"
                            :key="doc.id"
                            class="p-3 rounded-xl border border-gray-100 flex items-center justify-between gap-3 hover:bg-gray-50 transition"
                        >
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal flex items-center justify-center shrink-0 border border-teal-100 font-bold uppercase text-[10px]">
                                    {{ doc.file_type || 'PDF' }}
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs font-bold text-gray-900 truncate">{{ doc.title }}</div>
                                    <div class="text-[10px] text-gray-400 flex items-center gap-2 mt-0.5">
                                        <span class="text-teal font-semibold">{{ doc.category }}</span>
                                        <span>•</span>
                                        <span>{{ formatSize(doc.file_size) }}</span>
                                        <span>•</span>
                                        <span>{{ doc.downloads_count }}x diunduh</span>
                                    </div>
                                </div>
                            </div>
                            <a
                                :href="route('documents.download', doc.id)"
                                class="p-1.5 rounded-lg text-gray-500 hover:text-teal hover:bg-teal-50 transition"
                                title="Unduh File"
                            >
                                <DownloadCloud class="w-4 h-4" />
                            </a>
                        </div>
                        <div v-if="!recentDocuments || recentDocuments.length === 0" class="text-center py-6 text-xs text-gray-400">
                            Belum ada dokumen yang diunggah.
                        </div>
                    </div>
                </div>

                <!-- Recent Photos Grid -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <ImagePlus class="w-4 h-4 text-blue-600" />
                            <span>Galeri Foto & Dokumentasi Kegiatan</span>
                        </h2>
                        <Link :href="route('admin.photos.index')" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <span>Kelola Galeri</span>
                            <ArrowRight class="w-3 h-3" />
                        </Link>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div
                            v-for="photo in recentPhotos"
                            :key="photo.id"
                            class="group relative h-28 rounded-xl overflow-hidden bg-gray-100 border border-gray-200"
                        >
                            <img :src="photo.image_path" :alt="photo.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-2.5 flex flex-col justify-end text-white">
                                <span class="text-[9px] font-extrabold uppercase text-amber-300">{{ photo.album_name }}</span>
                                <h3 class="text-[11px] font-bold line-clamp-1">{{ photo.title }}</h3>
                            </div>
                        </div>
                        <div v-if="!recentPhotos || recentPhotos.length === 0" class="col-span-2 text-center py-6 text-xs text-gray-400">
                            Belum ada foto dalam galeri.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
