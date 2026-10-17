<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppBadge from '@/Components/AppBadge.vue';
import { 
    Calendar, 
    MapPin, 
    Video, 
    CheckCircle2,
    Sparkles 
} from 'lucide-vue-next';

defineProps<{
    events: any;
    registeredEventIds: number[];
}>();
</script>

<template>
    <PublicLayout>
        <Head title="Kalender Kegiatan - Community Hub GENREVERSE" />

        <div class="py-12 bg-gradient-to-b from-blue-100/60 via-sky-50 to-[#F0F7FF] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Header with Jamboree Photo Banner -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center bg-white p-6 md:p-8 rounded-3xl border-2 border-blue-100 shadow-md">
                    <div class="md:col-span-8 space-y-3">
                        <AppBadge variant="teal">🎉 Community Hub & Festival</AppBadge>
                        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            Kalender Kegiatan GenRe ✨
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 max-w-2xl leading-relaxed">
                            Yuk ikuti webinar virtual inspiratif, jambore remaja, dan workshop pendidik sebaya PIK-R di daerahmu. Perluas relasi positif dan raih <strong class="text-blue-600 font-extrabold">+15 XP</strong> tiap pendaftaran!
                        </p>
                    </div>
                    <div class="md:col-span-4 hidden md:flex justify-end">
                        <div class="relative rounded-2xl overflow-hidden border-2 border-amber-300 shadow-lg max-w-xs rotate-1 hover:rotate-0 transition-transform">
                            <img
                                src="/images/community_cover.jpg"
                                alt="GenRe Jamboree Festival"
                                class="w-full h-40 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent flex items-end p-2.5">
                                <span class="text-[10px] font-black text-amber-300">🎪 Festival & Temu Remaja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Grid with Photos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="evt in events.data"
                        :key="evt.id"
                        class="bg-white rounded-3xl border-2 border-blue-100 overflow-hidden flex flex-col justify-between hover:shadow-2xl hover:border-blue-300 transition-all duration-300 group"
                    >
                        <div>
                            <!-- Event Cover Photo -->
                            <div class="relative h-44 overflow-hidden bg-blue-100">
                                <img
                                    src="/images/community_cover.jpg"
                                    :alt="evt.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                                <span
                                    class="absolute top-3 left-3 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-xs"
                                    :class="evt.is_online ? 'bg-indigo-600 text-white' : 'bg-blue-600 text-white'"
                                >
                                    {{ evt.is_online ? 'Webinar Online' : 'Tatap Muka' }}
                                </span>
                                <span class="absolute top-3 right-3 text-[10px] font-bold text-slate-800 bg-white/95 px-2 py-0.5 rounded-md shadow-xs">
                                    {{ evt.region?.name }}
                                </span>
                            </div>

                            <div class="p-6 space-y-3">
                                <div class="text-xs font-black text-amber-600 flex items-center gap-1.5">
                                    <Calendar class="w-4 h-4" />
                                    <span>{{ new Date(evt.starts_at).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
                                </div>

                                <h2 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ evt.title }}
                                </h2>

                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ evt.description }}
                                </p>

                                <div class="text-xs text-slate-500 flex items-center gap-1.5 pt-1">
                                    <component :is="evt.is_online ? Video : MapPin" class="w-3.5 h-3.5 text-blue-500 shrink-0" />
                                    <span class="truncate">{{ evt.location_label }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
                            <div v-if="registeredEventIds.includes(evt.id)" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600" />
                                <span>Terdaftar 🎉</span>
                            </div>
                            <span v-else class="text-xs text-slate-400 font-medium">
                                {{ evt.capacity ? `Kuota: ${evt.capacity} Orang` : 'Kuota Terbuka' }}
                            </span>

                            <Link
                                :href="route('events.show', evt.id)"
                                class="px-4 py-2 rounded-xl text-xs font-black transition shadow-sm"
                                :class="registeredEventIds.includes(evt.id) ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-blue-600 text-white hover:bg-blue-700'"
                            >
                                {{ registeredEventIds.includes(evt.id) ? 'Lihat Detail' : 'Daftar (+15 XP)' }}
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="events.links?.length > 3" class="flex justify-center gap-2 pt-6">
                    <Link
                        v-for="(link, i) in events.links"
                        :key="i"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
                        :class="[
                            link.active ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-blue-50 border border-blue-200',
                            !link.url ? 'opacity-40 pointer-events-none' : ''
                        ]"
                    />
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
