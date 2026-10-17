<script setup lang="ts">
withDefaults(defineProps<{
    size?: 'sm' | 'md' | 'lg' | 'xl';
    light?: boolean;
    showText?: boolean;
}>(), {
    size: 'md',
    light: false,
    showText: true,
});
</script>

<template>
    <div class="flex items-center gap-3 select-none group">
        <!-- Direct Clean Logo Emblem without container box -->
        <img
            src="/logogenre.svg"
            alt="Logo GenRe"
            :class="[
                'object-contain transition-transform duration-200 group-hover:scale-105 shrink-0 max-h-14',
                size === 'sm' ? 'h-8 w-auto' : size === 'lg' ? 'h-12 w-auto' : size === 'xl' ? 'h-14 w-auto' : 'h-10 w-auto'
            ]"
        />

        <!-- Official Brand Typography -->
        <div v-if="showText" class="flex flex-col leading-none whitespace-nowrap">
            <span 
                :class="[
                    'font-black tracking-tight font-sans',
                    size === 'lg' ? 'text-2xl' : size === 'sm' ? 'text-base' : size === 'xl' ? 'text-3xl' : 'text-xl',
                    light ? 'text-white' : 'text-[#101D35]'
                ]"
            >
                GENRE<span :class="light ? 'text-[#FFC21A]' : 'text-[#2166F3]'">VERSE</span>
            </span>
            <span 
                :class="[
                    'font-semibold tracking-wide mt-1',
                    size === 'lg' ? 'text-xs' : size === 'sm' ? 'text-[10px]' : size === 'xl' ? 'text-sm' : 'text-[11px]',
                    light ? 'text-blue-200' : 'text-[#101D35]/65'
                ]"
            >
                Satu Semesta, Sejuta Asa
            </span>
        </div>
    </div>
</template>

