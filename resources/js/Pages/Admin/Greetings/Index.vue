<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    Plus,
    Search, 
    HeartHandshake, 
    Check, 
    X, 
    Star, 
    Trash2, 
    Edit3,
    Clock, 
    CheckCircle2, 
    XCircle,
    User,
    Sparkles,
    UploadCloud,
    MessageSquare
} from 'lucide-vue-next';

const props = defineProps<{
    greetings: {
        data: any[];
        links: any[];
        total: number;
        current_page: number;
        last_page: number;
    };
    stats: {
        total: number;
        pending: number;
        approved: number;
        rejected: number;
        featured: number;
    };
    filters: {
        search?: string;
        status?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || 'all');
const showModal = ref(false);
const editingGreeting = ref<any>(null);

const form = ref({
    name: '',
    role_title: 'Duta GenRe Indonesia',
    institution: '',
    message: '',
    photo: null as File | null,
    photo_url: '',
    status: 'approved',
    is_featured: false,
});

const filePreview = ref<string | null>(null);

const handleSearch = () => {
    router.get(
        route('admin.greetings.index'),
        { search: search.value, status: selectedStatus.value },
        { preserveState: true, replace: true }
    );
};

const filterByStatus = (status: string) => {
    selectedStatus.value = status;
    handleSearch();
};

const openCreateModal = () => {
    editingGreeting.value = null;
    form.value = {
        name: '',
        role_title: 'Duta GenRe Indonesia',
        institution: '',
        message: '',
        photo: null,
        photo_url: '',
        status: 'approved',
        is_featured: false,
    };
    filePreview.value = null;
    showModal.value = true;
};

const openEditModal = (greet: any) => {
    editingGreeting.value = greet;
    form.value = {
        name: greet.name,
        role_title: greet.role_title,
        institution: greet.institution || '',
        message: greet.message,
        photo: null,
        photo_url: greet.photo_path || '',
        status: greet.status,
        is_featured: Boolean(greet.is_featured),
    };
    filePreview.value = greet.photo_path || null;
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
    formData.append('name', form.value.name);
    formData.append('role_title', form.value.role_title);
    formData.append('institution', form.value.institution);
    formData.append('message', form.value.message);
    formData.append('status', form.value.status);
    formData.append('is_featured', form.value.is_featured ? '1' : '0');

    if (form.value.photo) {
        formData.append('photo', form.value.photo);
    }
    if (form.value.photo_url) {
        formData.append('photo_url', form.value.photo_url);
    }

    if (editingGreeting.value) {
        router.post(route('admin.greetings.update', editingGreeting.value.id), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    } else {
        router.post(route('admin.greetings.store'), formData, {
            onSuccess: () => {
                showModal.value = false;
            },
        });
    }
};

const updateStatus = (greeting: any, status: string) => {
    router.post(route('admin.greetings.status', greeting.id), { status }, { preserveScroll: true });
};

const toggleFeatured = (greeting: any) => {
    router.post(route('admin.greetings.toggle_featured', greeting.id), {}, { preserveScroll: true });
};

const deleteGreeting = (greeting: any) => {
    if (confirm(`Yakin ingin menghapus ucapan dari "${greeting.name}"?`)) {
        router.delete(route('admin.greetings.destroy', greeting.id), { preserveScroll: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Ucapan & Harapan Remaja - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <HeartHandshake class="w-7 h-7 text-forest" />
                        <span>Kelola Ucapan, Harapan & Testimoni</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Kelola aspirasi, pesan inspiratif, dan testimoni remaja se-Indonesia secara lengkap (Tambah, Edit, Moderasi, Hapus).</p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition"
                >
                    <Plus class="w-4 h-4" />
                    <span>Tambah Ucapan Baru</span>
                </button>
            </div>

            <!-- Stats Overview Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase text-gray-400">Total Masuk</span>
                    <div class="text-2xl font-black text-gray-900">{{ stats.total }}</div>
                    <div class="text-[10px] text-gray-400">Semua Ucapan & Foto</div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase text-amber-600">Menunggu Review</span>
                    <div class="text-2xl font-black text-amber-600">{{ stats.pending }}</div>
                    <div class="text-[10px] text-gray-400">Perlu Moderasi Admin</div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase text-emerald-600">Disetujui Tayang</span>
                    <div class="text-2xl font-black text-emerald-600">{{ stats.approved }}</div>
                    <div class="text-[10px] text-gray-400">Tampil di Beranda</div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm space-y-1">
                    <span class="text-[11px] font-bold uppercase text-blue-600">Sorotan Unggulan</span>
                    <div class="text-2xl font-black text-blue-600">{{ stats.featured }}</div>
                    <div class="text-[10px] text-gray-400">Pin di Baris Depan</div>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                    <button
                        @click="filterByStatus('all')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0"
                        :class="selectedStatus === 'all' ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    >
                        Semua Status
                    </button>
                    <button
                        @click="filterByStatus('pending')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0 flex items-center gap-1.5"
                        :class="selectedStatus === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                    >
                        <Clock class="w-3.5 h-3.5" />
                        <span>Menunggu ({{ stats.pending }})</span>
                    </button>
                    <button
                        @click="filterByStatus('approved')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0 flex items-center gap-1.5"
                        :class="selectedStatus === 'approved' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                    >
                        <CheckCircle2 class="w-3.5 h-3.5" />
                        <span>Disetujui</span>
                    </button>
                    <button
                        @click="filterByStatus('rejected')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition shrink-0 flex items-center gap-1.5"
                        :class="selectedStatus === 'rejected' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100'"
                    >
                        <XCircle class="w-3.5 h-3.5" />
                        <span>Ditolak</span>
                    </button>
                </div>

                <div class="relative w-full md:w-72">
                    <Search class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Cari nama, asal, ucapan..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-gray-200 focus:outline-none focus:ring-2 focus:ring-forest focus:border-forest"
                    />
                </div>
            </div>

            <!-- Greetings Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div 
                    v-for="greet in greetings.data" 
                    :key="greet.id" 
                    class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4"
                >
                    <!-- Header with user photo & info -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-forest-50 border border-forest-200 shrink-0">
                                <img 
                                    v-if="greet.photo_path" 
                                    :src="greet.photo_path" 
                                    :alt="greet.name" 
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-forest">
                                    <User class="w-6 h-6" />
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-900 leading-tight">{{ greet.name }}</h3>
                                <p class="text-[11px] font-semibold text-forest mt-0.5">{{ greet.role_title }}</p>
                                <p v-if="greet.institution" class="text-[10px] text-gray-400">{{ greet.institution }}</p>
                            </div>
                        </div>

                        <!-- Status badge -->
                        <span 
                            class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase shrink-0"
                            :class="{
                                'bg-emerald-50 text-emerald-700 border border-emerald-200': greet.status === 'approved',
                                'bg-amber-50 text-amber-700 border border-amber-200': greet.status === 'pending',
                                'bg-rose-50 text-rose-700 border border-rose-200': greet.status === 'rejected'
                            }"
                        >
                            {{ greet.status }}
                        </span>
                    </div>

                    <!-- Message Body -->
                    <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100 text-xs text-gray-700 leading-relaxed italic relative">
                        "{{ greet.message }}"
                    </div>

                    <!-- Footer Moderation Actions & Edit/Delete -->
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <button
                            @click="toggleFeatured(greet)"
                            class="flex items-center gap-1 text-[11px] font-bold px-2 py-1 rounded-lg transition"
                            :class="greet.is_featured ? 'text-amber-600 bg-amber-50' : 'text-gray-400 hover:text-amber-500'"
                            title="Tampilkan di baris sorotan utama"
                        >
                            <Star class="w-3.5 h-3.5" :class="{ 'fill-amber-500': greet.is_featured }" />
                            <span>{{ greet.is_featured ? 'Sorotan' : 'Sorot' }}</span>
                        </button>

                        <div class="flex items-center gap-1.5">
                            <button
                                v-if="greet.status !== 'approved'"
                                @click="updateStatus(greet, 'approved')"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-bold text-[10px] hover:bg-emerald-700 transition"
                                title="Setujui Ucapan"
                            >
                                <Check class="w-3 h-3" />
                                <span>Setujui</span>
                            </button>
                            <button
                                v-if="greet.status !== 'rejected'"
                                @click="updateStatus(greet, 'rejected')"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px] hover:bg-amber-200 transition"
                                title="Tolak Ucapan"
                            >
                                <X class="w-3 h-3" />
                                <span>Tolak</span>
                            </button>
                            <button
                                @click="openEditModal(greet)"
                                class="p-1 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-forest transition"
                                title="Edit Ucapan"
                            >
                                <Edit3 class="w-3.5 h-3.5" />
                            </button>
                            <button
                                @click="deleteGreeting(greet)"
                                class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition"
                                title="Hapus permanen"
                            >
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="greetings.data.length === 0" class="bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                Tidak ada data ucapan yang sesuai filter saat ini.
            </div>

            <!-- Pagination -->
            <div v-if="greetings.links && greetings.links.length > 3" class="flex justify-center gap-1 pt-4">
                <template v-for="(link, index) in greetings.links" :key="index">
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

        <!-- Create & Edit Greeting Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">
                            {{ editingGreeting ? 'Edit Ucapan & Testimoni' : 'Tambah Ucapan Baru' }}
                        </h2>
                        <p class="text-xs text-gray-500">Kelola informasi nama, peran, asal instansi, foto avatar, dan pesan harapan.</p>
                    </div>
                    <button @click="showModal = false" class="p-1.5 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap *</label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Contoh: Rian Pratama"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Peran / Jabatan *</label>
                            <input
                                v-model="form.role_title"
                                type="text"
                                required
                                placeholder="Contoh: Duta GenRe Putra / Konselor Sebaya"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Asal Daerah / Instansi</label>
                        <input
                            v-model="form.institution"
                            type="text"
                            placeholder="Contoh: Provinsi Jawa Barat / UIN Syarif Hidayatullah"
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pesan Ucapan & Harapan *</label>
                        <textarea
                            v-model="form.message"
                            rows="4"
                            required
                            placeholder="Tuliskan pesan harapan, motivasi, atau aspirasi bagi generasi berencana Indonesia..."
                            class="w-full px-3.5 py-2.5 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest leading-relaxed"
                        ></textarea>
                    </div>

                    <!-- Photo Upload File / URL -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Foto Avatar / Profil</label>
                        <div class="flex items-center gap-4">
                            <label class="cursor-pointer flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 border border-dashed border-gray-300 hover:bg-gray-100 text-xs font-semibold text-gray-600 transition">
                                <UploadCloud class="w-4 h-4 text-forest" />
                                <span>Pilih Foto</span>
                                <input type="file" accept="image/*" @change="handleFileChange" class="hidden" />
                            </label>
                            <span class="text-[11px] text-gray-400">atau</span>
                            <input
                                v-model="form.photo_url"
                                type="url"
                                placeholder="Tautan URL foto profil online"
                                class="flex-1 px-3 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            />
                        </div>
                        <div v-if="filePreview || form.photo_url" class="mt-3 flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-200">
                                <img :src="filePreview || form.photo_url" alt="Preview Avatar" class="w-full h-full object-cover" />
                            </div>
                            <span class="text-[11px] text-gray-400">Pratinjau Avatar</span>
                        </div>
                    </div>

                    <!-- Status & Featured -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status Moderasi *</label>
                            <select
                                v-model="form.status"
                                class="w-full px-3.5 py-2 rounded-xl text-xs border border-gray-200 focus:ring-2 focus:ring-forest focus:border-forest"
                            >
                                <option value="approved">Disetujui (Tayang di Publik)</option>
                                <option value="pending">Menunggu Review</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-5">
                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                                id="is_featured_greeting"
                                class="rounded text-forest focus:ring-forest"
                            />
                            <label for="is_featured_greeting" class="text-xs font-bold text-gray-700">Tampilkan di Sorotan Utama</label>
                        </div>
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
                            {{ editingGreeting ? 'Simpan Perubahan' : 'Tambah Ucapan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
