<script setup lang="ts">
import { LucideIcon, Sparkles } from 'lucide-vue-next';

defineProps<{
    title: string;
    description: string;
    icon?: any;
}>();
</script>

<template>
    <div class="text-center py-12 px-4 rounded-2xl border-2 border-dashed border-gray-200 bg-white/60 flex flex-col items-center justify-center space-y-3">
        <div class="w-12 h-12 rounded-2xl bg-forest-50 text-forest flex items-center justify-center shadow-inner">
            <component :is="icon || Sparkles" class="w-6 h-6" />
        </div>
        <div class="space-y-1 max-w-sm">
            <h3 class="text-sm font-bold text-gray-900">{{ title }}</h3>
            <p class="text-xs text-gray-500 leading-relaxed">{{ description }}</p>
        </div>
        <div v-if="$slots.action" class="pt-2">
            <slot name="action" />
        </div>
    </div>
</template>
