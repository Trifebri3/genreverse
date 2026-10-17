<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Calendar, 
    MapPin, 
    Clock, 
    Users, 
    Video, 
    ArrowLeft, 
    CheckCircle2, 
    Sparkles 
} from 'lucide-vue-next';

const props = defineProps<{
    event: any;
    isRegistered: boolean;
    registrationsCount: number;
}>();

const page = usePage();
const auth = page.props.auth as any;

const regForm = useForm({});

function register() {
    regForm.post(route('events.register', props.event.id));
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${event.title} - Kegiatan GenRe`" />

        <div class="py-12 bg-gradient-to-b from-forest-50 to-[#F7F8F3] min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Back -->
                <Link :href="route('events.index')" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-forest transition">
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke Kalender Kegiatan</span>
                </Link>

                <div class="bg-white rounded-3xl border border-forest/10 p-6 sm:p-10 shadow-sm space-y-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <AppBadge :variant="event.is_online ? 'teal' : 'forest'">
                            {{ event.is_online ? 'Webinar Online' : 'Kegiatan Tatap Muka' }}
                        </AppBadge>
                        <span class="text-xs font-bold text-gray-500">{{ event.region?.name }}</span>
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-2xl sm:text-4xl font-black text-forest tracking-tight">
                            {{ event.title }}
                        </h1>
                        <div class="text-xs text-gray-500">
                            Penyelenggara: <strong class="text-gray-800">{{ event.organizer?.profile?.display_name ?? event.organizer?.name }}</strong>
                        </div>
                    </div>

                    <!-- Event Logistics Card -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl bg-[#F7F8F3] border border-forest/10 text-xs text-gray-700">
                        <div class="flex items-center gap-2.5">
                            <Calendar class="w-5 h-5 text-forest" />
                            <div>
                                <div class="font-bold text-gray-900">Waktu Pelaksanaan</div>
                                <div>{{ new Date(event.starts_at).toLocaleDateString('id-ID', { dateStyle: 'long' }) }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <component :is="event.is_online ? Video : MapPin" class="w-5 h-5 text-forest" />
                            <div class="overflow-hidden">
                                <div class="font-bold text-gray-900">Lokasi / Kanal</div>
                                <div class="truncate">{{ event.location_label }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <Users class="w-5 h-5 text-forest" />
                            <div>
                                <div class="font-bold text-gray-900">Peserta Terdaftar</div>
                                <div>{{ registrationsCount }} {{ event.capacity ? `/ ${event.capacity} Kuota` : 'Orang' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Body -->
                    <div class="space-y-3 text-sm text-gray-700 leading-relaxed whitespace-pre-line border-t border-gray-100 pt-6">
                        <h3 class="text-base font-bold text-forest">Deskripsi & Agenda Kegiatan</h3>
                        <p>{{ event.description }}</p>
                    </div>

                    <!-- Registration Box -->
                    <div class="p-6 rounded-2xl bg-forest-50 border border-forest-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="space-y-1 text-center sm:text-left">
                            <div class="text-xs font-bold text-teal uppercase">Partisipasi Acara</div>
                            <h4 class="text-base font-extrabold text-forest">
                                {{ isRegistered ? 'Kamu Sudah Terdaftar di Acara Ini!' : 'Tertarik Mengikuti Kegiatan Ini?' }}
                            </h4>
                            <p class="text-xs text-gray-600">
                                {{ isRegistered ? 'Simpan jadwal di kalendermu dan hadiri tepat waktu.' : 'Pendaftaran gratis dan dapatkan +15 XP.' }}
                            </p>
                        </div>

                        <template v-if="auth?.user">
                            <button
                                v-if="!isRegistered"
                                @click="register"
                                :disabled="regForm.processing"
                                class="px-6 py-3.5 rounded-xl bg-forest text-white font-extrabold text-sm hover:bg-forest-600 transition shadow flex items-center gap-2 shrink-0 disabled:opacity-50"
                            >
                                <Sparkles class="w-4 h-4 text-sunshine" />
                                <span>{{ regForm.processing ? 'Mendaftar...' : 'Daftar Sekarang (+15 XP)' }}</span>
                            </button>
                            <div v-else class="px-5 py-2.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-2 border border-emerald-200">
                                <CheckCircle2 class="w-4 h-4 text-emerald-600" />
                                <span>Terdaftar Resmi</span>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('register')" class="px-6 py-3 rounded-xl bg-sunshine text-forest font-black text-xs hover:bg-sunshine-300 transition shadow">
                                Daftar Akun untuk Mengikuti
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
