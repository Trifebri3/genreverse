<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}>();
</script>

<template>
    <div v-if="links && links.length > 3" class="flex flex-wrap items-center justify-center gap-1.5 py-4">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="px-3 py-1.5 text-xs text-gray-400 border border-gray-200 rounded-lg select-none"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition"
                :class="[
                    link.active
                        ? 'bg-forest text-white border-forest shadow-sm'
                        : 'bg-white text-gray-700 border-gray-200 hover:border-forest hover:text-forest'
                ]"
                v-html="link.label"
            />
        </template>
    </div>
</template>
