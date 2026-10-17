<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Plus, 
    Search, 
    ImagePlus, 
    Star, 
    Trash2, 
    Edit3,
    Calendar, 
    X,
    UploadCloud,
    Folder,
    Maximize2
} from 'lucide-vue-next';

const props = defineProps<{
    photos: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
    };
    albums: string[];
    filters: {
        search?: string;
        album?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedAlbum = ref(props.filters.album || 'all');
const showModal = ref(false);
const editingPhoto = ref<any>(null);
const lightboxPhoto = ref<any>(null);

const form = ref({
    title: '',
    album_name: 'Kegiatan GenRe',
    caption: '',
    photo: null as File | null,
    photo_url: '',
    event_date: new Date().toISOString().split('T')[0],
    is_featured: false,
});

const filePreview = ref<string | null>(null);

const handleSearch = () => {
    router.get(
        route('admin.photos.index'),
        { search: search.value, album: selectedAlbum.value },
        { preserveState: true, replace: true }
    );
};

const filterByAlbum = (album: string) => {
    selectedAlbum.value = album;
    handleSearch();
};

const openCreateModal = () => {
    editingPhoto.value = null;
    form.value = {
        title: '',
        album_name: 'Kegiatan GenRe',
        caption: '',
        photo: null,
        photo_url: '',
        event_date: new Date().toISOString().split('T')[0],
        is_featured: false,
    };
    filePreview.value = null;
    showModal.value = true;
};

const openEditModal = (photo: any) => {
    editingPhoto.value = photo;
    form.value = {
        title: photo.title,
        album_name: photo.album_name,
        caption: photo.caption || '',
        photo: null,
        photo_url: photo.image_path || '',
        event_date: photo.event_date ? photo.event_date.split('T')[0] : new Date().toISOString().split('T')[0],
        is_featured: Boolean(photo.is_featured),
    };
    filePreview.value = photo.image_path || null;
    showModal.value = true;
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.value.photo = target.files[0];
        filePreview.value = URL.createObjectURL(target.files[0]);
    }
};

const submitForm = () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('album_name', form.value.album_name);
    formData.append('caption', form.value.caption);
    formData.append('event_date', form.value.event_date);
    formData.append('is_featured', form.value.is_featured ? '1' : '0');

    if (form.value.photo) {
        formData.append('photo', form.value.photo);
    }
    if (form.value.photo_url) {
        formData.append('photo_url', form.value.photo_url);
    }

    if (editingPhoto.value) {
        router.post(route('admin.photos.update', editingPhoto.value.id), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    } else {
        router.post(route('admin.photos.store'), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    }
};

const toggleFeatured = (photo: any) => {
    router.post(route('admin.photos.toggle_featured', photo.id), {}, { preserveScroll: true });
};

const deletePhoto = (photo: any) => {
    if (confirm(`Yakin ingin menghapus foto "${photo.title}"?`)) {
        router.delete(route('admin.photos.destroy', photo.id), { preserveScroll: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Galeri Foto Kegiatan - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <ImagePlus class="w-7 h-7 text-forest" />
                        <span>Galeri Foto & Dokumentasi Kegiatan</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Upload dan dokumentasikan momen aksi nyata, jambore, pelatihan Duta, dan kegiatan remaja (Tambah, Edit, Hapus).</p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition"
                >
                    <Plus class="w-4 h-4" />
                    <span>Upload Foto Baru</span>
                </button>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <button
                        @click="filterByAlbum('all')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0"
                        :class="selectedAlbum === 'all' ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                        Semua Album
                    </button>
                    <button
                        v-for="alb in albums"
                        :key="alb"
                        @click="filterByAlbum(alb)"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0 flex items-center gap-1.5"
                        :class="selectedAlbum === alb ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                        <Folder class="w-3.5 h-3.5" />
                        <span>{{ alb }}</span>
                    </button>
                </div>

                <div class="relative w-full md:w-72">
                    <Search class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Cari judul atau caption..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-gray-200 focus:outline-none focus:ring-2 focus:ring-forest focus:border-forest"
                    />
                </div>
            </div>

            <!-- Photos Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <div 
                    v-for="photo in photos.data" 
                    :key="photo.id" 
                    class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group"
                >
                    <!-- Image Card Cover -->
                    <div class="relative h-44 bg-gray-100 overflow-hidden cursor-pointer" @click="lightboxPhoto = photo">
                        <img 
                            :src="photo.image_path" 
                            :alt="photo.title" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="p-2 bg-white/90 text-gray-800 rounded-full shadow-md">
                                <Maximize2 class="w-4 h-4" />
                            </span>
                        </div>
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-black/60 text-white backdrop-blur-md">
                                {{ photo.album_name }}
                            </span>
                        </div>
                        <button
                            @click.stop="toggleFeatured(photo)"
                            class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full flex items-center justify-center transition backdrop-blur-md"
                            :class="photo.is_featured ? 'bg-amber-400 text-forest shadow-md' : 'bg-black/50 text-white hover:bg-black/70'"
                            :title="photo.is_featured ? 'Hapus dari sorotan' : 'Jadikan foto sorotan utama'"
                        >
                            <Star class="w-4 h-4" :class="{ 'fill-forest': photo.is_featured }" />
                        </button>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <h3 class="font-bold text-sm text-gray-900 line-clamp-1">{{ photo.title }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ photo.caption || 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1 text-[11px]">
                                <Calendar class="w-3.5 h-3.5" />
                                <span>{{ photo.event_date ? new Date(photo.event_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Terbaru' }}</span>
                            </span>
                            <div class="flex items-center gap-1">
                                <button
                                    @click="openEditModal(photo)"
                                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                    title="Edit Foto"
                                >
                                    <Edit3 class="w-4 h-4" />
                                </button>
                                <button
                                    @click="deletePhoto(photo)"
                                    class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition"
                                    title="Hapus Foto"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="photos.data.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                Belum ada foto dalam galeri. Silakan klik tombol "Upload Foto Baru".
            </div>

            <!-- Pagination -->
            <div v-if="photos.links && photos.links.length > 3" class="flex justify-center gap-1 pt-4">
                <template v-for="(link, index) in photos.links" :key="index">
                    <button
                        v-if="link.url"
                        @click="router.get(link.url, {}, { preserveState: true })"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                        :class="link.active ? 'bg-forest text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>

        <!-- Lightbox Zoom Preview Modal -->
        <div v-if="lightboxPhoto" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" @click="lightboxPhoto = null">
            <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col" @click.stop>
                <div class="relative bg-black flex items-center justify-center max-h-[70vh] overflow-hidden">
                    <img :src="lightboxPhoto.image_path" :alt="lightboxPhoto.title" class="max-h-[70vh] w-auto object-contain" />
                    <button 
                        @click="lightboxPhoto = null"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>
                <div class="p-5 bg-white space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold text-forest bg-forest-50 px-2.5 py-0.5 rounded-full">{{ lightboxPhoto.album_name }}</span>
                        <span class="text-xs text-gray-400">{{ lightboxPhoto.event_date ? new Date(lightboxPhoto.event_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '' }}</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">{{ lightboxPhoto.title }}</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ lightboxPhoto.caption || 'Tidak ada keterangan tambahan.' }}</p>
                </div>
            </div>
        </div>

        <!-- Create & Edit Photo Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">
                            {{ editingPhoto ? 'Edit Informasi Foto' : 'Unggah Foto Kegiatan' }}
                        </h2>
                        <p class="text-xs text-gray-500">Mendukung format JPG, PNG, WEBP hingga 10MB atau tautan gambar online.</p>
                    </div>
                    <button @click="showModal = false" class="p-1.5 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Judul / Nama Kegiatan *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Contoh: Apresiasi Duta GenRe Nasional 2026"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Album *</label>
                            <input
                                v-model="form.album_name"
                                type="text"
                                required
                                placeholder="Contoh: Ajang Kreativitas / SAPA GenRe"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kegiatan</label>
                            <input
                                v-model="form.event_date"
                                type="date"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Caption Foto</label>
                        <textarea
                            v-model="form.caption"
                            rows="2"
                            placeholder="Ceritakan singkat tentang momen dalam foto ini..."
                            class="w-full px-3.5 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <!-- Photo Upload File / URL -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            {{ editingPhoto ? 'Ganti File Foto atau Tautan (Opsional)' : 'Pilih File Foto atau Tautan *' }}
                        </label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 border border-dashed border-gray-300 hover:bg-gray-100 text-xs font-semibold text-gray-600 transition">
                                <UploadCloud class="w-4 h-4 text-forest" />
                                <span>Pilih Gambar</span>
                                <input type="file" accept="image/*" @change="handleFileChange" class="hidden" />
                            </label>
                            <span class="text-[11px] text-gray-400">atau</span>
                            <input
                                v-model="form.photo_url"
                                type="url"
                                placeholder="Tautan URL gambar online"
                                class="flex-1 px-3 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                        <div v-if="filePreview || form.photo_url" class="mt-3 relative w-48 h-32 rounded-xl overflow-hidden border border-gray-200">
                            <img :src="filePreview || form.photo_url" alt="Preview" class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <!-- Featured Toggle -->
                    <div class="flex items-center gap-2 pt-2">
                        <input
                            v-model="form.is_featured"
                            type="checkbox"
                            id="is_featured_photo"
                            class="rounded text-forest focus:ring-forest"
                        />
                        <label for="is_featured_photo" class="text-xs font-bold text-gray-700">Tampilkan di Sorotan Utama Beranda</label>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-forest text-white hover:bg-forest-700 shadow-sm transition"
                        >
                            {{ editingPhoto ? 'Simpan Perubahan' : 'Simpan ke Galeri' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
