<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Plus, 
    Search, 
    Eye, 
    Trash2, 
    Edit3, 
    Newspaper, 
    CheckCircle2, 
    XCircle, 
    ExternalLink,
    X,
    UploadCloud
} from 'lucide-vue-next';

const props = defineProps<{
    articles: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
    };
    categories: string[];
    filters: {
        search?: string;
        category?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || 'all');
const showModal = ref(false);
const editingArticle = ref<any>(null);

const form = ref({
    title: '',
    category: 'Kegiatan',
    excerpt: '',
    content: '',
    author_name: 'Humas GenRe Indonesia',
    cover_image: null as File | null,
    cover_image_url: '',
    is_published: true,
});

const filePreview = ref<string | null>(null);

const handleSearch = () => {
    router.get(
        route('admin.articles.index'),
        { search: search.value, category: selectedCategory.value },
        { preserveState: true, replace: true }
    );
};

const filterByCategory = (cat: string) => {
    selectedCategory.value = cat;
    handleSearch();
};

const openCreateModal = () => {
    editingArticle.value = null;
    form.value = {
        title: '',
        category: 'Kegiatan',
        excerpt: '',
        content: '',
        author_name: 'Humas GenRe Indonesia',
        cover_image: null,
        cover_image_url: '',
        is_published: true,
    };
    filePreview.value = null;
    showModal.value = true;
};

const openEditModal = (art: any) => {
    editingArticle.value = art;
    form.value = {
        title: art.title,
        category: art.category,
        excerpt: art.excerpt,
        content: art.content,
        author_name: art.author_name,
        cover_image: null,
        cover_image_url: art.cover_image || '',
        is_published: Boolean(art.is_published),
    };
    filePreview.value = art.cover_image || null;
    showModal.value = true;
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.value.cover_image = target.files[0];
        filePreview.value = URL.createObjectURL(target.files[0]);
    }
};

const submitForm = () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('category', form.value.category);
    formData.append('excerpt', form.value.excerpt);
    formData.append('content', form.value.content);
    formData.append('author_name', form.value.author_name);
    formData.append('is_published', form.value.is_published ? '1' : '0');
    
    if (form.value.cover_image) {
        formData.append('cover_image', form.value.cover_image);
    }
    if (form.value.cover_image_url) {
        formData.append('cover_image_url', form.value.cover_image_url);
    }

    if (editingArticle.value) {
        router.post(route('admin.articles.update', editingArticle.value.id), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    } else {
        router.post(route('admin.articles.store'), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    }
};

const togglePublish = (art: any) => {
    router.post(route('admin.articles.toggle_publish', art.id), {}, { preserveScroll: true });
};

const deleteArticle = (art: any) => {
    if (confirm(`Yakin ingin menghapus berita "${art.title}"?`)) {
        router.delete(route('admin.articles.destroy', art.id), { preserveScroll: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Kelola Berita & Publikasi - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <Newspaper class="w-7 h-7 text-forest" />
                        <span>Manajemen Berita & Publikasi</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Publikasikan kabar terbaru, kegiatan remaja, liputan aksi, dan pengumuman resmi nasional.</p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition"
                >
                    <Plus class="w-4 h-4" />
                    <span>Tulis Berita Baru</span>
                </button>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <button
                        @click="filterByCategory('all')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0"
                        :class="selectedCategory === 'all' ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                        Semua Kategori
                    </button>
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        @click="filterByCategory(cat)"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0"
                        :class="selectedCategory === cat ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                        {{ cat }}
                    </button>
                </div>

                <div class="relative w-full md:w-72">
                    <Search class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Cari judul berita..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-gray-200 focus:outline-none focus:ring-2 focus:ring-forest focus:border-forest"
                    />
                </div>
            </div>

            <!-- Articles Table -->
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase font-black tracking-wider text-[11px]">
                            <tr>
                                <th class="py-3.5 px-4">Cover & Judul</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Penulis</th>
                                <th class="py-3.5 px-4 text-center">Dibaca</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="art in articles.data" :key="art.id" class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-14 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                                            <img 
                                                v-if="art.cover_image" 
                                                :src="art.cover_image" 
                                                :alt="art.title" 
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                                <Newspaper class="w-5 h-5" />
                                            </div>
                                        </div>
                                        <div class="max-w-md">
                                            <a 
                                                :href="route('articles.show', art.slug)" 
                                                target="_blank" 
                                                class="font-bold text-gray-900 hover:text-forest line-clamp-1 transition flex items-center gap-1.5"
                                            >
                                                <span>{{ art.title }}</span>
                                                <ExternalLink class="w-3 h-3 text-gray-400" />
                                            </a>
                                            <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ art.excerpt }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ art.category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-gray-700">{{ art.author_name || 'Admin' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 font-bold text-gray-700">
                                        <Eye class="w-3.5 h-3.5 text-gray-400" />
                                        <span>{{ art.views_count }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button
                                        @click="togglePublish(art)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition"
                                        :class="art.is_published ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        :title="art.is_published ? 'Klik untuk simpan ke draft' : 'Klik untuk publikasikan'"
                                    >
                                        <component :is="art.is_published ? CheckCircle2 : XCircle" class="w-3.5 h-3.5" />
                                        <span>{{ art.is_published ? 'Tayang' : 'Draft' }}</span>
                                    </button>
                                </td>
                                 <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a
                                            :href="route('articles.show', art.slug)"
                                            target="_blank"
                                            class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                            title="Buka Halaman Publik"
                                        >
                                            <ExternalLink class="w-4 h-4" />
                                        </a>
                                        <button
                                            @click="openEditModal(art)"
                                            class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                            title="Edit Berita"
                                        >
                                            <Edit3 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteArticle(art)"
                                            class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition"
                                            title="Hapus Berita"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="articles.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-gray-400">
                                    Belum ada berita yang ditemukan. Silakan tambahkan berita baru.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="articles.links && articles.links.length > 3" class="flex justify-center gap-1 pt-4">
                <template v-for="(link, index) in articles.links" :key="index">
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

        <!-- Create / Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">
                            {{ editingArticle ? 'Edit Berita' : 'Tulis Berita Baru' }}
                        </h2>
                        <p class="text-xs text-gray-500">Kelola informasi publikasi dan materi edukasi resmi.</p>
                    </div>
                    <button @click="showModal = false" class="p-1.5 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form @submit.prevent="submitForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Judul Berita *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Contoh: Duta GenRe 2026 Gelar Pelatihan Konselor Sebaya"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kategori *</label>
                            <select
                                v-model="form.category"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest bg-white"
                            >
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="Pengumuman">Pengumuman</option>
                                <option value="Advokasi">Advokasi</option>
                                <option value="Edukasi">Edukasi</option>
                                <option value="Prestasi">Prestasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Penulis / Tim Humas</label>
                            <input
                                v-model="form.author_name"
                                type="text"
                                placeholder="Humas GenRe Indonesia"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Ringkasan Singkat (Excerpt) *</label>
                        <textarea
                            v-model="form.excerpt"
                            rows="2"
                            required
                            placeholder="Tulis ringkasan 1-2 kalimat pengantar berita..."
                            class="w-full px-3.5 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Konten Lengkap Berita *</label>
                        <textarea
                            v-model="form.content"
                            rows="6"
                            required
                            placeholder="Tulis narasi berita lengkap di sini. Mendukung paragraf teks atau tag HTML..."
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <!-- Upload Cover Image -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Upload Gambar Cover</label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 border border-dashed border-gray-300 hover:bg-gray-100 text-xs font-semibold text-gray-600 transition">
                                <UploadCloud class="w-4 h-4 text-forest" />
                                <span>Pilih File Gambar</span>
                                <input type="file" accept="image/*" @change="handleFileChange" class="hidden" />
                            </label>
                            <span class="text-[11px] text-gray-400">atau</span>
                            <input
                                v-model="form.cover_image_url"
                                type="url"
                                placeholder="Masukkan URL gambar (opsional)"
                                class="flex-1 px-3 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                        <div v-if="filePreview" class="mt-3 relative w-36 h-24 rounded-xl overflow-hidden border border-gray-200">
                            <img :src="filePreview" alt="Preview" class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <!-- Publish Toggle -->
                    <div class="flex items-center gap-2 pt-2">
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            id="is_published"
                            class="rounded text-forest focus:ring-forest"
                        />
                        <label for="is_published" class="text-xs font-bold text-gray-700">Langsung publikasikan sekarang</label>
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
                            {{ editingArticle ? 'Simpan Perubahan' : 'Terbitkan Berita' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
