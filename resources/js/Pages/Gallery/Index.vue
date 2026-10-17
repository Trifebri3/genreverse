<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Search, 
    ImagePlus, 
    Calendar, 
    X, 
    Sparkles, 
    Folder, 
    Maximize2,
    Star
} from 'lucide-vue-next';

const props = defineProps<{
    photos: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
    };
    albums: string[];
    featuredPhotos: any[];
    filters: {
        search?: string;
        album?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedAlbum = ref(props.filters.album || 'all');
const activePhotoModal = ref<any>(null);

const handleSearch = () => {
    router.get(
        route('gallery.index'),
        { search: search.value, album: selectedAlbum.value },
        { preserveState: true, replace: true }
    );
};

const filterByAlbum = (album: string) => {
    selectedAlbum.value = album;
    handleSearch();
};
</script>

<template>
    <PublicLayout>
        <Head title="Galeri Dokumentasi Kegiatan - GENREVERSE" />

        <div class="space-y-12 pb-16">
            <!-- Hero Banner -->
            <section class="relative bg-gradient-to-r from-blue-700 to-forest text-white rounded-3xl p-8 sm:p-12 overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-2xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-sunshine text-forest">
                        <ImagePlus class="w-3.5 h-3.5" />
                        <span>Momen & Dokumentasi Aksi</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-white">
                        Galeri Kegiatan & Kilas Semesta Duta GenRe
                    </h1>
                    <p class="text-sm text-blue-100 leading-relaxed">
                        Rekaman visual jejak langkah remaja Indonesia dalam mengedukasi sebaya, menyuarakan advokasi pencegahan stunting, dan merajut harapan masa depan.
                    </p>

                    <!-- Search Input -->
                    <div class="pt-2 max-w-md">
                        <div class="relative">
                            <Search class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="search"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Cari kegiatan atau momen foto..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sunshine"
                            />
                        </div>
                    </div>
                </div>

                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-sunshine/10 blur-3xl pointer-events-none"></div>
            </section>

            <!-- Album Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <button
                    @click="filterByAlbum('all')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 shadow-sm"
                    :class="selectedAlbum === 'all' ? 'bg-forest text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                >
                    Semua Album
                </button>
                <button
                    v-for="alb in albums"
                    :key="alb"
                    @click="filterByAlbum(alb)"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition shrink-0 shadow-sm flex items-center gap-1.5"
                    :class="selectedAlbum === alb ? 'bg-forest text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                >
                    <Folder class="w-3.5 h-3.5" />
                    <span>{{ alb }}</span>
                </button>
            </div>

            <!-- Photos Masonry Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div
                    v-for="photo in photos.data"
                    :key="photo.id"
                    @click="activePhotoModal = photo"
                    class="bg-white rounded-2xl border border-gray-200/90 overflow-hidden shadow-sm hover:shadow-lg transition cursor-pointer group flex flex-col justify-between"
                >
                    <!-- Photo Container -->
                    <div class="relative h-56 bg-gray-100 overflow-hidden">
                        <img
                            :src="photo.image_path"
                            :alt="photo.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        />
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="w-10 h-10 rounded-full bg-white/90 text-forest flex items-center justify-center shadow">
                                <Maximize2 class="w-5 h-5" />
                            </span>
                        </div>
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-black/60 text-white backdrop-blur-md">
                                {{ photo.album_name }}
                            </span>
                        </div>
                        <div v-if="photo.is_featured" class="absolute top-2.5 right-2.5">
                            <span class="w-7 h-7 rounded-full bg-amber-400 text-forest flex items-center justify-center shadow">
                                <Star class="w-3.5 h-3.5 fill-forest" />
                            </span>
                        </div>
                    </div>

                    <!-- Photo Details -->
                    <div class="p-4 space-y-1.5">
                        <h3 class="font-bold text-xs text-gray-900 group-hover:text-forest transition line-clamp-1">
                            {{ photo.title }}
                        </h3>
                        <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                            {{ photo.caption || 'Dokumentasi kegiatan resmi GenRe.' }}
                        </p>
                        <div class="pt-2 text-[10px] text-gray-400 flex items-center gap-1">
                            <Calendar class="w-3 h-3" />
                            <span>{{ photo.event_date ? new Date(photo.event_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Terbaru' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="photos.data.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                Belum ada foto dalam album ini.
            </div>
        </div>

        <!-- Photo Lightbox Modal -->
        <div 
            v-if="activePhotoModal" 
            class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm"
            @click.self="activePhotoModal = null"
        >
            <div class="bg-white rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl relative">
                <button
                    @click="activePhotoModal = null"
                    class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition"
                >
                    <X class="w-5 h-5" />
                </button>

                <div class="max-h-[70vh] bg-black flex items-center justify-center overflow-hidden">
                    <img 
                        :src="activePhotoModal.image_path" 
                        :alt="activePhotoModal.title" 
                        class="max-h-[70vh] w-auto object-contain"
                    />
                </div>

                <div class="p-6 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-forest-50 text-forest border border-forest-200">
                            {{ activePhotoModal.album_name }}
                        </span>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs text-gray-400">{{ activePhotoModal.event_date }}</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900">{{ activePhotoModal.title }}</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ activePhotoModal.caption }}</p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
