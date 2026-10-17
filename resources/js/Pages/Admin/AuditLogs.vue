<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppPagination from '@/Components/AppPagination.vue';
import AppEmptyState from '@/Components/AppEmptyState.vue';
import { 
    ScrollText, 
    Search, 
    User, 
    Clock, 
    ShieldCheck, 
    Database, 
    Info 
} from 'lucide-vue-next';

interface AuditLog {
    id: number;
    actor_id: number | null;
    action: string;
    subject_type: string;
    subject_id: number | null;
    metadata: Record<string, any> | null;
    created_at: string;
    actor?: {
        name: string;
        profile?: {
            display_name: string;
        };
    };
}

const props = defineProps<{
    logs: {
        data: AuditLog[];
        links: any[];
        total: number;
    };
    selectedAction: string | null;
}>();

const searchInput = ref(props.selectedAction || '');

const handleSearch = () => {
    router.get(route('admin.audit_logs.index'), {
        action: searchInput.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const filterByQuickAction = (act: string | null) => {
    searchInput.value = act || '';
    handleSearch();
};
</script>

<template>
    <AdminLayout>
        <Head title="Audit Log Aktivitas Sistem - Admin GENREVERSE" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 flex items-center gap-2.5">
                        <ScrollText class="w-6 h-6 text-forest" />
                        <span>Audit Trail & Log Aktivitas Sistem</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Catatan audit transparansi dan integritas tindakan administratif tingkat nasional dan wilayah.
                    </p>
                </div>
                <div class="text-xs font-bold text-gray-500 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
                    Total: <span class="text-forest font-black">{{ logs.total }}</span> Entri Log
                </div>
            </div>

            <!-- Security & Privacy Notice -->
            <div class="bg-forest-50 border border-forest-200 p-3.5 rounded-2xl flex items-start gap-3 text-xs text-forest-800">
                <ShieldCheck class="w-5 h-5 text-forest shrink-0 mt-0.5" />
                <div class="space-y-0.5">
                    <span class="font-bold">Kepatuhan UU Perlindungan Data & Keamanan (Security Requirement #11)</span>
                    <p class="text-[11px] text-forest-700 leading-relaxed">
                        Audit log mencatat aktor, aksi, dan subjek secara permanen. Sistem secara otomatis membatasi metadata dan tidak pernah menyimpan kata sandi, token autentikasi, atau data rahasia pengguna.
                    </p>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <form @submit.prevent="handleSearch" class="relative flex-1 max-w-md">
                    <Search class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="searchInput"
                        type="text"
                        placeholder="Cari aksi (cth: campaign, report, quiz, story)..."
                        class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-gray-300 focus:border-forest focus:ring-forest"
                    />
                </form>

                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-gray-400 text-[11px] font-bold mr-1">Filter Cepat:</span>
                    <button
                        @click="filterByQuickAction(null)"
                        class="px-2.5 py-1 rounded-lg font-bold border transition text-[11px]"
                        :class="!selectedAction ? 'bg-forest text-white border-forest' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'"
                    >
                        Semua
                    </button>
                    <button
                        @click="filterByQuickAction('campaign')"
                        class="px-2.5 py-1 rounded-lg font-bold border transition text-[11px]"
                        :class="selectedAction === 'campaign' ? 'bg-forest text-white border-forest' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'"
                    >
                        Kampanye
                    </button>
                    <button
                        @click="filterByQuickAction('report')"
                        class="px-2.5 py-1 rounded-lg font-bold border transition text-[11px]"
                        :class="selectedAction === 'report' ? 'bg-forest text-white border-forest' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'"
                    >
                        Verifikasi
                    </button>
                    <button
                        @click="filterByQuickAction('moderation')"
                        class="px-2.5 py-1 rounded-lg font-bold border transition text-[11px]"
                        :class="selectedAction === 'moderation' ? 'bg-forest text-white border-forest' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'"
                    >
                        Moderasi
                    </button>
                </div>
            </div>

            <!-- Audit Logs Table -->
            <div v-if="logs.data.length > 0" class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Aktor</th>
                                <th class="py-3 px-4">Tindakan (Action)</th>
                                <th class="py-3 px-4">Target / Subjek</th>
                                <th class="py-3 px-4">Metadata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50/80 transition">
                                <td class="py-3 px-4 text-gray-500 whitespace-nowrap font-mono text-[11px]">
                                    {{ new Date(log.created_at).toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'medium' }) }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                        <User class="w-3.5 h-3.5 text-gray-400" />
                                        <span>{{ log.actor?.profile?.display_name ?? log.actor?.name ?? 'Sistem Otomatis' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-forest bg-forest-50 px-2 py-0.5 rounded border border-forest-100 font-mono text-[11px]">
                                        {{ log.action }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap font-mono text-[11px]">
                                    {{ log.subject_type ? `${log.subject_type} #${log.subject_id ?? ''}` : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <pre v-if="log.metadata" class="text-[10px] text-gray-600 bg-gray-50 p-1.5 rounded border border-gray-100 max-w-xs truncate overflow-x-auto font-mono">{{ JSON.stringify(log.metadata) }}</pre>
                                    <span v-else class="text-gray-300 italic">-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-100">
                    <AppPagination :links="logs.links" />
                </div>
            </div>

            <!-- Empty State -->
            <AppEmptyState
                v-else
                title="Log Tidak Ditemukan"
                description="Tidak ada catatan audit yang cocok dengan kata kunci pencarian yang dimasukkan."
                :icon="ScrollText"
            />
        </div>
    </AdminLayout>
</template>
