<script setup lang="ts">
import GenreLogo from '@/Components/GenreLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#F5F7FA] pt-8 sm:justify-center sm:pt-0"
    >
        <div class="mb-6">
            <Link href="/">
                <GenreLogo size="xl" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800"
        >
            <slot />
        </div>
    </div>
</template>
