<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    MessageSquareQuote, 
    Send, 
    ShieldCheck, 
    Lock, 
    HelpCircle, 
    CheckCircle2, 
    PhoneCall,
    Sparkles
} from 'lucide-vue-next';

const props = defineProps<{
    questions: any;
    selectedCategory?: string;
}>();

const page = usePage();
const auth = page.props.auth as any;

const askForm = useForm({
    category: 'Relasi Sehat & Batasan Diri',
    question_body: '',
    is_anonymous: true,
});

function submitQuestion() {
    askForm.post(route('ask.store'), {
        onSuccess: () => {
            askForm.reset('question_body');
        }
    });
}

// Answer modal for ambassadors
const answeringQuestion = ref<any | null>(null);
const answerForm = useForm({
    answer_body: '',
    referral_resources: 'Pusat Informasi & Konseling Remaja (PIK-R) terdekat dan Halo Kemenkes 1500-567',
});

function openAnswerModal(q: any) {
    answeringQuestion.value = q;
}

function submitAnswer() {
    if (!answeringQuestion.value) return;
    answerForm.post(route('ask.answer', answeringQuestion.value.id), {
        onSuccess: () => {
            answeringQuestion.value = null;
            answerForm.reset('answer_body');
        }
    });
}
</script>

<template>
    <PublicLayout>
        <Head title="SAPA GenRe - Sarana Aspirasi dan Pertanyaan Remaja | GENREVERSE" />

        <div class="py-12 bg-gradient-to-b from-blue-100/60 via-sky-50 to-[#F0F7FF] min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <!-- Header with Counselor Photo Card -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center bg-white p-6 md:p-8 rounded-3xl border-2 border-blue-100 shadow-md">
                    <div class="md:col-span-8 space-y-3">
                        <AppBadge variant="teal">💬 Ruang Interaktif Remaja</AppBadge>
                        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            SAPA GenRe ✨
                        </h1>
                        <div class="text-base sm:text-lg font-black text-blue-700">
                            Sarana Aspirasi dan Pertanyaan Remaja
                        </div>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                            SAPA GenRe merupakan ruang interaktif dalam platform GENREVERSE yang menjadi wadah bagi remaja untuk menyampaikan pertanyaan, berbagi cerita, dan memperoleh informasi seputar dunia remaja bersama Duta GenRe. Melalui komunikasi yang terbuka, edukatif, dan suportif, SAPA GenRe hadir untuk membantu remaja menemukan pemahaman, menguatkan diri, serta mengambil keputusan yang tepat dalam merencanakan masa depan.
                        </p>
                    </div>

                    <!-- Counselor Photo Card -->
                    <div class="md:col-span-4 flex justify-center md:justify-end">
                        <div class="p-3 bg-blue-50 border-2 border-blue-200 rounded-3xl flex items-center gap-3.5 shadow-sm">
                            <img
                                src="/images/peer_counselor.jpg"
                                alt="Kak Sari Konselor Sebaya"
                                class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-md shrink-0"
                            />
                            <div>
                                <div class="text-[10px] font-black uppercase text-blue-600">Konselor Sebaya SAPA GenRe</div>
                                <div class="text-xs font-black text-slate-900">Kak Sari & Rekan Duta</div>
                                <div class="text-[10px] text-slate-500 font-semibold">"Ruang terbuka, edukatif & suportif 💖"</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ask Question Box (Top Card) -->
                <div class="bg-white rounded-3xl border-2 border-blue-100 p-6 sm:p-8 shadow-md space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-slate-900 flex items-center justify-center font-bold">
                            <MessageSquareQuote class="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Sampaikan Aspirasi & Pertanyaanmu</h2>
                            <p class="text-xs text-slate-500">Pertanyaan dan ceritamu dijaga kerahasiaannya, dijawab oleh Duta GenRe dan tenaga berkompeten untuk mendampingimu.</p>
                        </div>
                    </div>

                    <form @submit.prevent="submitQuestion" class="space-y-4 text-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Topik Konsultasi</label>
                                <select
                                    v-model="askForm.category"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                >
                                    <option value="Relasi Sehat & Batasan Diri">Relasi Sehat & Batasan Diri (Toxic Relationship)</option>
                                    <option value="Kesehatan Reproduksi & Pubertas">Kesehatan Reproduksi & Pubertas</option>
                                    <option value="Perencanaan Masa Depan (PUP)">Perencanaan Masa Depan & Usia Menikah</option>
                                    <option value="Kesehatan Mental & Stres Belajar">Kesehatan Mental & Stres Belajar</option>
                                    <option value="Tekanan Teman Sebaya (Peer Pressure)">Tekanan Teman Sebaya & Rokok/Napza</option>
                                </select>
                            </div>

                            <div class="flex items-center pt-6">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input
                                        v-model="askForm.is_anonymous"
                                        type="checkbox"
                                        class="rounded text-forest focus:ring-forest w-4 h-4"
                                    />
                                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                        <Lock class="w-3.5 h-3.5 text-teal" />
                                        <span>Kirim sebagai Pertanyaan Anonim (Identitas Dirahasiakan)</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Pertanyaan atau Keresahanmu</label>
                            <textarea
                                v-model="askForm.question_body"
                                rows="3"
                                required
                                placeholder="Tuliskan secara jelas apa yang sedang kamu hadapi..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                            ></textarea>
                            <div v-if="askForm.errors.question_body" class="text-xs text-rose-600 mt-1">{{ askForm.errors.question_body }}</div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                <ShieldCheck class="w-4 h-4 text-emerald-600" />
                                <span>Privasi terjamin 100%</span>
                            </span>
                            <button
                                type="submit"
                                :disabled="askForm.processing"
                                class="px-6 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition shadow flex items-center gap-1.5 disabled:opacity-50"
                            >
                                <Send class="w-3.5 h-3.5 text-sunshine" />
                                <span>{{ askForm.processing ? 'Mengirim...' : 'Kirim Pertanyaan' }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Emergency Helpline Banner -->
                <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-3">
                        <PhoneCall class="w-5 h-5 text-amber-700 shrink-0" />
                        <div>
                            <strong class="text-amber-900 block">Menghadapi Situasi Darurat atau Krisis Emosional Berat?</strong>
                            <span class="text-amber-800">Hubungi layanan konseling resmi Kemenkes di <strong>Halo Kemenkes 1500-567</strong> atau Sahabat Anak Sejiwa <strong>119 ext 8</strong>.</span>
                        </div>
                    </div>
                </div>

                <!-- Published Questions & Answers -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold text-forest">Tanya Jawab Terverifikasi</h2>
                        <span class="text-xs text-gray-500 font-semibold">{{ questions.data.length }} Pertanyaan Terjawab</span>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="q in questions.data"
                            :key="q.id"
                            class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-8 shadow-sm space-y-4"
                        >
                            <!-- Question Bar -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs text-gray-400 font-medium">
                                    <span class="font-bold text-teal bg-teal-50 px-2.5 py-0.5 rounded-md">{{ q.category }}</span>
                                    <span>No. Rujukan: {{ q.public_reference }}</span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">
                                    "{{ q.question_body }}"
                                </h3>
                            </div>

                            <!-- Answer Card -->
                            <div v-if="q.answers?.length" class="p-5 rounded-2xl bg-forest-50 border border-forest-100 space-y-3 text-sm">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="font-extrabold text-forest flex items-center gap-1.5">
                                        <CheckCircle2 class="w-4 h-4 text-emerald-600" />
                                        <span>Dijawab oleh: {{ q.answers[0].author?.profile?.display_name ?? q.answers[0].author?.name }}</span>
                                    </div>
                                    <span class="text-gray-400">{{ new Date(q.answers[0].created_at).toLocaleDateString('id-ID', { dateStyle: 'medium' }) }}</span>
                                </div>
                                <div class="text-gray-700 leading-relaxed whitespace-pre-line text-xs sm:text-sm">
                                    {{ q.answers[0].answer_body }}
                                </div>
                                <div v-if="q.answers[0].referral_resources" class="pt-2 border-t border-forest-100 text-xs text-teal font-semibold">
                                    Rujukan: {{ q.answers[0].referral_resources }}
                                </div>
                            </div>

                            <!-- Ambassador Answer Button (if pending or allowed) -->
                            <div v-if="(auth?.user?.is_ambassador || auth?.user?.is_admin)" class="pt-2 flex justify-end">
                                <button
                                    @click="openAnswerModal(q)"
                                    class="px-4 py-1.5 rounded-xl border border-forest/30 text-xs font-bold text-forest hover:bg-forest-50 transition"
                                >
                                    Tulis / Perbarui Jawaban Duta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AMBASSADOR ANSWER MODAL -->
                <div v-if="answeringQuestion" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full text-gray-900 shadow-2xl border border-forest/20 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-black text-forest">Jawab Pertanyaan Remaja</h3>
                                <p class="text-xs text-gray-500">Rujukan: {{ answeringQuestion.public_reference }}</p>
                            </div>
                            <button @click="answeringQuestion = null" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700 italic">
                            "{{ answeringQuestion.question_body }}"
                        </div>

                        <form @submit.prevent="submitAnswer" class="space-y-4 text-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Teks Jawaban Edukatif</label>
                                <textarea
                                    v-model="answerForm.answer_body"
                                    rows="5"
                                    required
                                    placeholder="Jawab dengan bahasa yang ramah, empatik, berbasis fakta ilmiah GenRe, dan tanpa menghakimi..."
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Rujukan Bantuan / Kontak Layanan</label>
                                <input
                                    v-model="answerForm.referral_resources"
                                    type="text"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-forest focus:ring-1 focus:ring-forest"
                                />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    @click="answeringQuestion = null"
                                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="answerForm.processing"
                                    class="px-5 py-2.5 rounded-xl bg-forest text-white text-xs font-bold hover:bg-forest-600 transition shadow disabled:opacity-50"
                                >
                                    {{ answerForm.processing ? 'Menyimpan...' : 'Terbitkan Jawaban' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
