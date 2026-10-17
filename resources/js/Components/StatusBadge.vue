<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    status: string;
}>();

const config = computed(() => {
    switch (props.status.toLowerCase()) {
        case 'published':
        case 'verified':
        case 'completed':
        case 'approved':
        case 'answered':
            return {
                label: props.status === 'published' ? 'Terbit' : props.status === 'verified' ? 'Terverifikasi' : props.status === 'completed' ? 'Selesai' : props.status === 'approved' ? 'Disetujui' : 'Terjawab',
                classes: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            };
        case 'in_review':
        case 'under_review':
        case 'pending_review':
        case 'pending':
            return {
                label: 'Menunggu Review',
                classes: 'bg-amber-50 text-amber-700 border-amber-200',
            };
        case 'draft':
            return {
                label: 'Draft',
                classes: 'bg-gray-100 text-gray-600 border-gray-200',
            };
        case 'rejected':
        case 'dismissed':
        case 'failed':
            return {
                label: props.status === 'rejected' ? 'Ditolak' : props.status === 'dismissed' ? 'Dilewati' : 'Gagal',
                classes: 'bg-rose-50 text-rose-700 border-rose-200',
            };
        default:
            return {
                label: props.status,
                classes: 'bg-gray-50 text-gray-700 border-gray-200',
            };
    }
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border',
            config.classes
        ]"
    >
        {{ config.label }}
    </span>
</template>
