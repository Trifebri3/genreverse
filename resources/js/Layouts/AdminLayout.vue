<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import GenreLogo from '@/Components/GenreLogo.vue';
import { 
    LayoutDashboard, 
    Newspaper,
    FolderArchive,
    ImagePlus,
    HeartHandshake,
    Megaphone, 
    FileCheck2, 
    ShieldAlert, 
    ScrollText, 
    Globe, 
    LogOut,
    Sparkles,
    UserCheck,
    Menu,
    X
} from 'lucide-vue-next';

const page = usePage();
const auth = computed(() => page.props.auth as any);
const flash = computed(() => page.props.flash as any);
const mobileMenuOpen = ref(false);

const adminNav = [
    { name: 'Dashboard Publikasi', href: route('admin.dashboard'), icon: LayoutDashboard },
    { name: 'Kelola Berita', href: route('admin.articles.index'), icon: Newspaper },
    { name: 'Dokumen & Laporan', href: route('admin.documents.index'), icon: FolderArchive },
    { name: 'Galeri Foto Kegiatan', href: route('admin.photos.index'), icon: ImagePlus },
    { name: 'Ucapan & Harapan', href: route('admin.greetings.index'), icon: HeartHandshake },
];

const isItemActive = (href: string) => {
    try {
        const path = new URL(href, 'http://localhost').pathname;
        return page.url.startsWith(path);
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="min-h-screen flex bg-gray-50 text-gray-900 font-sans">
        <!-- Sidebar Desktop -->
        <aside class="w-64 bg-forest text-white flex flex-col shrink-0 border-r border-forest-600 hidden md:flex">
            <!-- Logo Section -->
            <div class="h-20 flex items-center px-6 border-b border-forest-600/60 bg-forest-700/40">
                <GenreLogo size="md" :light="true" />
            </div>

            <!-- Role Badge -->
            <div class="p-4 mx-4 my-3 rounded-xl bg-forest-700/70 border border-sunshine/30 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-sunshine text-forest flex items-center justify-center font-bold">
                    <UserCheck class="w-5 h-5" />
                </div>
                <div class="overflow-hidden">
                    <div class="text-xs font-bold text-white truncate">{{ auth?.user?.name || 'King Nasar' }}</div>
                    <div class="text-[10px] text-sunshine font-extrabold tracking-wide uppercase">
                        Administrator Publikasi
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
                <div class="px-2 pb-1 text-[10px] font-bold tracking-wider uppercase text-forest-300">Menu Utama</div>
                <Link
                    v-for="item in adminNav"
                    :key="item.name"
                    :href="item.href"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition"
                    :class="[
                        isItemActive(item.href)
                            ? 'bg-sunshine text-forest font-bold shadow-sm'
                            : 'text-forest-200 hover:text-white hover:bg-forest-600/70'
                    ]"
                >
                    <component :is="item.icon" class="w-4 h-4" />
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Bottom Action: Return to Website & Logout -->
            <div class="p-4 border-t border-forest-600/60 space-y-1">
                <Link
                    :href="route('home')"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-forest-200 hover:text-white hover:bg-forest-600/50 transition"
                >
                    <Globe class="w-4 h-4" />
                    <span>Lihat Website Publik</span>
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-rose-300 hover:text-rose-100 hover:bg-rose-900/30 transition"
                >
                    <LogOut class="w-4 h-4" />
                    <span>Keluar dari Sesi</span>
                </Link>
            </div>
        </aside>

        <!-- Mobile Drawer Overlay -->
        <div 
            v-if="mobileMenuOpen" 
            class="fixed inset-0 z-50 bg-black/50 md:hidden flex"
            @click.self="mobileMenuOpen = false"
        >
            <div class="w-72 bg-forest text-white h-full flex flex-col p-4">
                <div class="flex items-center justify-between pb-4 border-b border-forest-600">
                    <GenreLogo size="md" :light="true" />
                    <button @click="mobileMenuOpen = false" class="p-1 rounded-lg text-white hover:bg-forest-600">
                        <X class="w-6 h-6" />
                    </button>
                </div>
                <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
                    <Link
                        v-for="item in adminNav"
                        :key="item.name"
                        :href="item.href"
                        @click="mobileMenuOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold"
                        :class="[
                            isItemActive(item.href)
                                ? 'bg-sunshine text-forest font-bold'
                                : 'text-forest-200 hover:text-white hover:bg-forest-600'
                        ]"
                    >
                        <component :is="item.icon" class="w-4 h-4" />
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
                <div class="pt-4 border-t border-forest-600">
                    <Link
                        :href="route('home')"
                        class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-forest-200 hover:text-white"
                    >
                        <Globe class="w-4 h-4" />
                        <span>Website Publik</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <button 
                        @click="mobileMenuOpen = true" 
                        class="p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100 md:hidden"
                        title="Buka Menu"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                    <span class="text-xs uppercase font-extrabold tracking-wider px-2.5 py-1 rounded-md bg-forest-50 text-forest border border-forest-200">
                        GENREVERSE Admin
                    </span>
                    <span class="text-xs text-gray-500 hidden sm:inline">Pusat Kendali Konten, Berita, Dokumen & Moderasi</span>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('home')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-200 text-gray-700 hover:border-forest hover:text-forest transition flex items-center gap-1.5"
                    >
                        <Globe class="w-3.5 h-3.5" />
                        <span>Lihat Website</span>
                    </Link>
                </div>
            </header>

            <!-- Flash Banner -->
            <div v-if="flash?.success" class="bg-emerald-600 text-white px-4 py-2.5 text-center text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 shadow-sm">
                <Sparkles class="w-4 h-4 text-sunshine shrink-0" />
                <span>{{ flash.success }}</span>
            </div>
            <div v-if="flash?.error" class="bg-rose-600 text-white px-4 py-2.5 text-center text-xs sm:text-sm font-semibold">
                <span>{{ flash.error }}</span>
            </div>

            <!-- Page Body Slot -->
            <main class="p-4 sm:p-6 lg:p-8 flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
