<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Plus, 
    Search, 
    FileText, 
    DownloadCloud, 
    Trash2, 
    Edit3, 
    FolderArchive, 
    FileCheck, 
    Lock, 
    Globe, 
    X,
    UploadCloud
} from 'lucide-vue-next';

const props = defineProps<{
    documents: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
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
const editingDoc = ref<any>(null);

const form = ref({
    title: '',
    category: 'Laporan Tahunan',
    description: '',
    file: null as File | null,
    is_public: true,
});

const handleSearch = () => {
    router.get(
        route('admin.documents.index'),
        { search: search.value, category: selectedCategory.value },
        { preserveState: true, replace: true }
    );
};

const filterByCategory = (cat: string) => {
    selectedCategory.value = cat;
    handleSearch();
};

const openCreateModal = () => {
    editingDoc.value = null;
    form.value = {
        title: '',
        category: 'Laporan Tahunan',
        description: '',
        file: null,
        is_public: true,
    };
    showModal.value = true;
};

const openEditModal = (doc: any) => {
    editingDoc.value = doc;
    form.value = {
        title: doc.title,
        category: doc.category,
        description: doc.description || '',
        file: null,
        is_public: Boolean(doc.is_public),
    };
    showModal.value = true;
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.value.file = target.files[0];
    }
};

const submitForm = () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('category', form.value.category);
    formData.append('description', form.value.description);
    formData.append('is_public', form.value.is_public ? '1' : '0');

    if (form.value.file) {
        formData.append('file', form.value.file);
    }

    if (editingDoc.value) {
        router.post(route('admin.documents.update', editingDoc.value.id), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    } else {
        router.post(route('admin.documents.store'), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    }
};

const deleteDoc = (doc: any) => {
    if (confirm(`Yakin ingin menghapus dokumen "${doc.title}"?`)) {
        router.delete(route('admin.documents.destroy', doc.id), { preserveScroll: true });
    }
};

const formatSize = (bytes: number) => {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
    return bytes + ' B';
};
</script>

<template>
    <AdminLayout>
        <Head title="Dokumen & Laporan - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <FolderArchive class="w-7 h-7 text-forest" />
                        <span>Pusat Dokumen & Laporan</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Kelola arsip laporan kegiatan, juknis, modul edukasi, dan dokumen resmi yang dapat diunduh publik.</p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition"
                >
                    <Plus class="w-4 h-4" />
                    <span>Unggah Dokumen Baru</span>
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
                        placeholder="Cari nama dokumen..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-gray-200 focus:outline-none focus:ring-2 focus:ring-forest focus:border-forest"
                    />
                </div>
            </div>

            <!-- Documents Table -->
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase font-black tracking-wider text-[11px]">
                            <tr>
                                <th class="py-3.5 px-4">Nama Dokumen</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4 text-center">Ukuran / Tipe</th>
                                <th class="py-3.5 px-4 text-center">Diunduh</th>
                                <th class="py-3.5 px-4 text-center">Akses</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="doc in documents.data" :key="doc.id" class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal flex items-center justify-center shrink-0 border border-teal-100 font-bold uppercase text-[10px]">
                                            {{ doc.file_type || 'PDF' }}
                                        </div>
                                        <div>
                                            <a 
                                                :href="route('documents.download', doc.id)" 
                                                class="font-bold text-gray-900 hover:text-forest line-clamp-1 transition flex items-center gap-1.5"
                                                title="Unduh Berkas"
                                            >
                                                <span>{{ doc.title }}</span>
                                                <DownloadCloud class="w-3.5 h-3.5 text-gray-400" />
                                            </a>
                                            <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ doc.description || doc.file_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-teal-50 text-teal-800 border border-teal-200">
                                        {{ doc.category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-semibold text-gray-600">{{ formatSize(doc.file_size) }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 font-bold text-gray-800">
                                        <DownloadCloud class="w-3.5 h-3.5 text-gray-400" />
                                        <span>{{ doc.downloads_count }}x</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                        :class="doc.is_public ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                                    >
                                        <component :is="doc.is_public ? Globe : Lock" class="w-3 h-3" />
                                        <span>{{ doc.is_public ? 'Publik' : 'Internal' }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a
                                            :href="route('documents.download', doc.id)"
                                            class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                            title="Unduh Berkas"
                                        >
                                            <DownloadCloud class="w-4 h-4" />
                                        </a>
                                        <button
                                            @click="openEditModal(doc)"
                                            class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                            title="Edit Dokumen"
                                        >
                                            <Edit3 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteDoc(doc)"
                                            class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition"
                                            title="Hapus Dokumen"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="documents.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-gray-400">
                                    Belum ada berkas dokumen atau laporan. Silakan unggah dokumen baru.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="documents.links && documents.links.length > 3" class="flex justify-center gap-1 pt-4">
                <template v-for="(link, index) in documents.links" :key="index">
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

        <!-- Upload / Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">
                            {{ editingDoc ? 'Edit Data Dokumen' : 'Unggah Dokumen / Laporan' }}
                        </h2>
                        <p class="text-xs text-gray-500">Mendukung format PDF, Word (DOC/DOCX), Excel (XLSX), PowerPoint, dan ZIP.</p>
                    </div>
                    <button @click="showModal = false" class="p-1.5 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Judul Dokumen / Laporan *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Contoh: Laporan Capaian Tahunan Program GenRe 2025"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Dokumen *</label>
                        <select
                            v-model="form.category"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest bg-white"
                        >
                            <option value="Laporan Tahunan">Laporan Tahunan</option>
                            <option value="Panduan & Modul">Panduan & Modul</option>
                            <option value="Petunjuk Teknis">Petunjuk Teknis</option>
                            <option value="Template Dokumen">Template Dokumen</option>
                            <option value="Regulasi & SK">Regulasi & SK</option>
                            <option value="Materi Sosialisasi">Materi Sosialisasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Deskripsi Singkat</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            placeholder="Jelaskan isi pokok dokumen atau petunjuk penggunaan..."
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <!-- File Upload Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            {{ editingDoc ? 'Ganti Berkas Dokumen (Opsional)' : 'Pilih Berkas Dokumen *' }}
                        </label>
                        <label class="cursor-pointer flex flex-col items-center justify-center p-6 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-300 hover:bg-gray-100 transition">
                            <UploadCloud class="w-8 h-8 text-forest mb-2" />
                            <span class="text-xs font-bold text-gray-700">
                                {{ form.file ? form.file.name : 'Klik untuk memilih file dokumen' }}
                            </span>
                            <span class="text-[10px] text-gray-400 mt-1">Maksimum ukuran file: 25 MB</span>
                            <input 
                                type="file" 
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip" 
                                @change="handleFileChange" 
                                :required="!editingDoc"
                                class="hidden" 
                            />
                        </label>
                    </div>

                    <!-- Public Access Toggle -->
                    <div class="flex items-center gap-2 pt-2">
                        <input
                            v-model="form.is_public"
                            type="checkbox"
                            id="is_public_doc"
                            class="rounded text-forest focus:ring-forest"
                        />
                        <label for="is_public_doc" class="text-xs font-bold text-gray-700">Tampilkan di Pusat Unduhan Publik</label>
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
                            {{ editingDoc ? 'Simpan Perubahan' : 'Unggah Dokumen' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
