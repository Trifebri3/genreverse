<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    BookHeart, 
    PlusCircle, 
    Sparkles, 
    Heart, 
    MessageSquare, 
    User as UserIcon,
    Send
} from 'lucide-vue-next';

defineProps<{
    stories: any;
}>();

const page = usePage();
const auth = page.props.auth as any;

const writeModalOpen = ref(false);

const form = useForm({
    title: '',
    body: '',
    publication_mode: 'pseudonym',
    pen_name: '',
});

function submitStory() {
    form.post(route('stories.store'), {
        onSuccess: () => {
            writeModalOpen.value = false;
            form.reset();
        }
    });
}
</script>

<template>
    <PublicLayout>
        <Head title="Cerita Kita - Ruang Berbagi Inspirasi Remaja" />

        <div class="py-12 bg-gradient-to-b from-forest-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="max-w-2xl space-y-3">
                        <AppBadge variant="forest">Cerita Kita</AppBadge>
                        <h1 class="text-3xl sm:text-5xl font-black text-forest tracking-tight">
                            Perspektif & Pengalaman Remaja
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">
                            Ruang berbagi refleksi, proses berdamai dengan rasa insecure, keberanian menolak tekanan sebaya, dan mimpi besar masa depan. Seluruh cerita dimoderasi agar tetap aman dan saling menguatkan.
                        </p>
                    </div>

                    <div>
                        <button
                            @click="writeModalOpen = true"
                            class="px-6 py-3.5 rounded-2xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow-lg flex items-center gap-2 shrink-0"
                        >
                            <PlusCircle class="w-5 h-5 text-sunshine" />
                            <span>Tulis Ceritamu (+25 XP)</span>
                        </button>
                    </div>
                </div>

                <!-- Stories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="story in stories.data"
                        :key="story.id"
                        class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-7 flex flex-col justify-between hover:shadow-xl transition-all duration-300 space-y-4"
                    >
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-teal bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-100">
                                    {{ story.publication_mode === 'anonymous' ? 'Anonim' : (story.pen_name || 'Remaja GenRe') }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ new Date(story.published_at || story.created_at).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }) }}
                                </span>
                            </div>

                            <h2 class="text-lg font-black text-forest line-clamp-2">
                                {{ story.title }}
                            </h2>

                            <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line line-clamp-5">
                                {{ story.body }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 font-medium">
                            <span class="flex items-center gap-1">
                                <Heart class="w-4 h-4 text-rose-500" />
                                <span>Terverifikasi Kurator</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- WRITE STORY MODAL -->
                <div v-if="writeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full text-gray-900 shadow-2xl border border-forest/20 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-black text-forest">Tulis Cerita Inspirasimu</h3>
                                <p class="text-xs text-gray-500">Berbagi pengalaman dapat menolong teman sebaya yang menghadapi hal serupa.</p>
                            </div>
                            <button @click="writeModalOpen = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                        </div>

                        <form @submit.prevent="submitStory" class="space-y-4 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Judul Cerita</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    required
                                    placeholder="Contoh: Belajar Berani Berkata Tidak pada Hal Berisiko"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Mode Publikasi</label>
                                    <select
                                        v-model="form.publication_mode"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                    >
                                        <option value="pseudonym">Nama Samaran (Pena)</option>
                                        <option value="anonymous">Anonim (Rahasia)</option>
                                        <option value="public_name">Nama Akun Saya</option>
                                    </select>
                                </div>

                                <div v-if="form.publication_mode === 'pseudonym'">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Samaran (Pena)</label>
                                    <input
                                        v-model="form.pen_name"
                                        type="text"
                                        placeholder="Contoh: Bintang Kejora"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Isi Cerita Pengalaman</label>
                                <textarea
                                    v-model="form.body"
                                    rows="5"
                                    required
                                    placeholder="Tuliskan pengalamanmu, apa yang kamu rasakan, bagaimana kamu melewatinya, dan apa pesanmu untuk teman sebaya..."
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-3">
                                <button
                                    type="button"
                                    @click="writeModalOpen = false"
                                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-5 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition shadow disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Mengirim...' : 'Kirim Cerita (+25 XP)' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
