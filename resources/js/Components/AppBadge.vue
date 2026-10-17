<script setup lang="ts">
withDefaults(
    defineProps<{
        variant?: 'forest' | 'teal' | 'sunshine' | 'gray' | 'danger';
        size?: 'sm' | 'md';
    }>(),
    {
        variant: 'forest',
        size: 'md',
    }
);
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 font-semibold rounded-full select-none',
            size === 'sm' ? 'px-2.5 py-0.5 text-xs' : 'px-3 py-1 text-xs md:text-sm',
            variant === 'forest' ? 'bg-forest-50 text-forest border border-forest-200/60' : '',
            variant === 'teal' ? 'bg-teal-50 text-teal border border-teal-200/60' : '',
            variant === 'sunshine' ? 'bg-sunshine-100 text-sunshine-900 border border-sunshine-300' : '',
            variant === 'gray' ? 'bg-gray-100 text-gray-700 border border-gray-200' : '',
            variant === 'danger' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '',
        ]"
    >
        <slot />
    </span>
</template>
