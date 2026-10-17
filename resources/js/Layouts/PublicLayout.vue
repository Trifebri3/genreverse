<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import GenreLogo from '@/Components/GenreLogo.vue';
import { 
    BookOpen, 
    Compass, 
    Megaphone, 
    Calendar, 
    MessageSquareQuote, 
    MapPin, 
    User as UserIcon, 
    Menu, 
    X, 
    Sparkles,
    ShieldCheck,
    LogOut,
    LayoutDashboard,
    HeartHandshake
} from 'lucide-vue-next';

const page = usePage();
const auth = computed(() => page.props.auth as any);
const flash = computed(() => page.props.flash as any);

const mobileMenuOpen = ref(false);

const navLinks = [
    { name: 'GenRe Academy', href: route('academy.index'), icon: BookOpen },
    { name: 'LifeQuest', href: route('lifequest.index'), icon: Compass },
    { name: 'Aksara Berdaya', href: route('campaigns.index'), icon: Megaphone },
    { name: 'Berita', href: route('articles.index'), icon: BookOpen },
    { name: 'Dokumen', href: route('documents.index'), icon: BookOpen },
    { name: 'Galeri', href: route('gallery.index'), icon: Compass },
    { name: 'SAPA GenRe', href: route('ask.index'), icon: MessageSquareQuote },
];

const isLinkActive = (href: string) => {
    try {
        const path = new URL(href, 'http://localhost').pathname;
        return page.url.startsWith(path);
    } catch {
        return false;
    }
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-[#F7F8F3] text-gray-900 pb-16 md:pb-0">
        <!-- Flash Alert -->
        <div v-if="flash?.success" class="bg-emerald-600 text-white px-4 py-2.5 text-center text-sm font-medium flex items-center justify-center gap-2 shadow-sm animate-fade-in">
            <Sparkles class="w-4 h-4 text-sunshine" />
            <span>{{ flash.success }}</span>
        </div>
        <div v-if="flash?.error" class="bg-rose-600 text-white px-4 py-2.5 text-center text-sm font-medium flex items-center justify-center gap-2 shadow-sm">
            <span>{{ flash.error }}</span>
        </div>

        <!-- Sticky Header (Clean Modern Dreams LMS Style) -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-3">
                    <GenreLogo size="md" />
                </Link>

                <!-- Desktop Navigation Links (Clean Text, No Icon Clutter) -->
                <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                    <Link
                        v-for="link in navLinks"
                        :key="link.name"
                        :href="link.href"
                        class="text-[15px] font-medium transition-colors duration-150 relative py-1"
                        :class="[
                            isLinkActive(link.href)
                                ? 'text-[#2166F3] font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-[#2166F3] after:rounded-full'
                                : 'text-slate-600 hover:text-[#2166F3]'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </nav>

                <!-- Auth Action Buttons (Desktop) -->
                <div class="hidden md:flex items-center gap-3">
                    <template v-if="auth?.user">
                        <!-- XP Counter Pill -->
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-xs font-bold text-[#2166F3]">
                            <Sparkles class="w-3.5 h-3.5 text-amber-500" />
                            <span>{{ auth.user.total_xp }} XP</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-300"></span>
                            <span>Lv. {{ auth.user.level }}</span>
                        </div>

                        <!-- Dashboard / Admin Link -->
                        <Link
                            :href="auth.user.is_admin ? route('admin.dashboard') : route('dashboard')"
                            class="px-4 py-2 rounded-full text-sm font-bold bg-[#2166F3] text-white hover:bg-blue-700 transition shadow-xs flex items-center gap-2"
                        >
                            <LayoutDashboard class="w-4 h-4 text-amber-300" />
                            <span>{{ auth.user.is_admin ? 'Admin Panel' : 'Dashboard' }}</span>
                        </Link>

                        <!-- Logout -->
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="p-2 rounded-full text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                            title="Keluar"
                        >
                            <LogOut class="w-4 h-4" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-5 py-2 text-[15px] font-semibold text-slate-700 hover:text-[#2166F3] transition"
                        >
                            Masuk
                        </Link>
                        <Link
                            :href="route('register')"
                            class="px-6 py-2 rounded-full border border-[#2166F3] text-[#2166F3] font-bold text-[14px] hover:bg-[#2166F3] hover:text-white transition-all shadow-xs"
                        >
                            Daftar
                        </Link>
                    </template>
                </div>

                <!-- Mobile Hamburger Toggle -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2 rounded-xl text-forest hover:bg-forest-50 transition focus:outline-none"
                    aria-label="Toggle menu"
                >
                    <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
                    <X v-else class="w-6 h-6" />
                </button>
            </div>

            <!-- Mobile Drawer Menu -->
            <div v-show="mobileMenuOpen" class="lg:hidden border-t border-forest/10 bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg animate-fade-in">
                <Link
                    v-for="link in navLinks"
                    :key="link.name"
                    :href="link.href"
                    @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-base font-semibold text-gray-700 hover:text-forest hover:bg-forest-50"
                >
                    <component :is="link.icon" class="w-5 h-5 text-forest" />
                    {{ link.name }}
                </Link>

                <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                    <template v-if="auth?.user">
                        <Link
                            :href="auth.user.is_admin ? route('admin.dashboard') : route('dashboard')"
                            @click="mobileMenuOpen = false"
                            class="w-full text-center py-2.5 rounded-xl font-bold bg-forest text-white"
                        >
                            Buka {{ auth.user.is_admin ? 'Admin Panel' : 'Dashboard' }}
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full text-center py-2 rounded-xl font-semibold text-rose-600 hover:bg-rose-50"
                        >
                            Keluar dari Akun
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            @click="mobileMenuOpen = false"
                            class="w-full text-center py-2.5 rounded-xl font-bold border border-forest/20 text-forest"
                        >
                            Masuk ke Akun
                        </Link>
                        <Link
                            :href="route('register')"
                            @click="mobileMenuOpen = false"
                            class="w-full text-center py-2.5 rounded-xl font-bold bg-sunshine text-forest"
                        >
                            Daftar Gratis
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Content Slot -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-[#0B132B] text-white pt-16 pb-12 border-t border-blue-900/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-white/10">
                    <!-- Column 1: Brand & Tagline -->
                    <div class="space-y-4 md:col-span-1">
                        <GenreLogo size="md" :light="true" />
                        <p class="text-xs text-blue-200/80 leading-relaxed">
                            GENREVERSE merupakan platform digital advokasi GenRe yang hadir sebagai ruang belajar, berbagi, dan bertumbuh bagi remaja. Menghadirkan kuis interaktif, layanan konseling, kumpulan modul edukasi, artikel inspiratif, serta informasi terkini seputar dunia remaja.
                        </p>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-950/80 border border-sunshine/30 text-xs font-semibold text-sunshine">
                            <HeartHandshake class="w-4 h-4 text-sunshine" />
                            <span>Dari Remaja, Oleh Remaja, Untuk Remaja</span>
                        </div>
                    </div>

                    <!-- Column 2: Program Utama -->
                    <div>
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-sunshine mb-4">Fitur & Publikasi</h4>
                        <ul class="space-y-2.5 text-sm text-blue-200/80">
                            <li><Link :href="route('academy.index')" class="hover:text-white transition">GenRe Academy</Link></li>
                            <li><Link :href="route('lifequest.index')" class="hover:text-white transition">LifeQuest Simulation</Link></li>
                            <li><Link :href="route('campaigns.index')" class="hover:text-white transition">Aksara Berdaya</Link></li>
                            <li><Link :href="route('articles.index')" class="hover:text-white transition">Warta & Berita GenRe</Link></li>
                            <li><Link :href="route('documents.index')" class="hover:text-white transition">Pusat Unduhan & Laporan</Link></li>
                            <li><Link :href="route('gallery.index')" class="hover:text-white transition">Galeri Foto Kegiatan</Link></li>
                            <li><Link :href="route('ask.index')" class="hover:text-white transition">SAPA GenRe & Konseling</Link></li>
                        </ul>
                    </div>

                    <!-- Column 3: 3 Salam GenRe -->
                    <div>
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-sunshine mb-4">Trilogi Nilai GenRe</h4>
                        <ul class="space-y-3 text-sm text-blue-200/80">
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-900/60 flex items-center justify-center text-xs font-bold text-sunshine shrink-0 mt-0.5">1</span>
                                <span><strong>Katakan Tidak</strong> pada Pernikahan Dini (<21 Th Putri, <25 Th Putra)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-900/60 flex items-center justify-center text-xs font-bold text-sunshine shrink-0 mt-0.5">2</span>
                                <span><strong>Katakan Tidak</strong> pada Seks Pranikah & Perilaku Berisiko</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-900/60 flex items-center justify-center text-xs font-bold text-sunshine shrink-0 mt-0.5">3</span>
                                <span><strong>Katakan Tidak</strong> pada NAPZA / Narkoba & Miras Oplosan</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 4: Keamanan & Legal -->
                    <div>
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-sunshine mb-4">Ruang Aman Remaja</h4>
                        <p class="text-xs text-blue-200/80 leading-relaxed mb-4">
                            GENREVERSE menerapkan prinsip <em>Safe by Design</em> dan mematuhi UU Pelindungan Data Pribadi (UU PDP). Privasimu terjamin dan terlindungi.
                        </p>
                        <div class="space-y-2 text-sm text-blue-200/80">
                            <div><Link :href="route('privacy')" class="hover:text-white transition">Kebijakan Privasi & Etika Data</Link></div>
                            <div><Link :href="route('about')" class="hover:text-white transition">Tentang GenRe Indonesia</Link></div>
                            <div><Link :href="route('impact_map')" class="hover:text-white transition">Peta Sebaran Dampak</Link></div>
                        </div>
                    </div>
                </div>

                <!-- POWERED BY PARTNERS SECTION -->
                <div class="py-8 border-b border-white/10 space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">
                        POWERED BY
                    </h4>
                    <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                        <a 
                            href="https://community.siyota.org" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="inline-block transition-transform duration-200 hover:scale-105"
                            title="THE COMMUNITY SPACE by YOTA"
                        >
                            <img 
                                src="https://community.siyota.org/csyota.webp" 
                                alt="THE COMMUNITY SPACE by YOTA" 
                                class="h-8 sm:h-9 w-auto object-contain brightness-0 invert opacity-95 hover:opacity-100 transition-opacity"
                                @error="($event.target as HTMLImageElement).src = '/images/csyota.webp'"
                            />
                        </a>
                        <div class="hidden sm:block h-7 w-[1.5px] bg-white/20"></div>
                        <a 
                            href="https://siyota.org" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="inline-block transition-transform duration-200 hover:scale-105"
                            title="Yota Adiwidya Center"
                        >
                            <img 
                                src="https://siyota.org/image/logo.png" 
                                alt="Yota Adiwidya Center" 
                                class="h-9 sm:h-10 w-auto object-contain brightness-0 invert opacity-95 hover:opacity-100 transition-opacity"
                                @error="($event.target as HTMLImageElement).src = '/images/yota_logo.png'"
                            />
                        </a>
                    </div>
                    <p class="text-xs text-blue-200/80 leading-relaxed max-w-xl">
                        Inisiatif sosial Yayasan Yota Wujudkan Asa dalam bidang teknologi &amp; kearifan lokal.
                    </p>
                </div>

                <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-blue-300/80 gap-4">
                    <p>&copy; 2026 GENREVERSE Indonesia. Satu Semesta, Sejuta Asa • Inisiatif Advokasi Remaja Duta GenRe & BKKBN.</p>
                    <p class="flex items-center gap-1.5">
                        <ShieldCheck class="w-4 h-4 text-sunshine" />
                        <span>Platform Terverifikasi & Ramah Remaja</span>
                    </p>
                </div>
            </div>
        </footer>

        <!-- Mobile Bottom App Bar (Mobile First Experience) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-forest/10 px-2 py-1.5 flex items-center justify-around shadow-2xl">
            <Link :href="route('home')" class="flex flex-col items-center py-1 px-3 text-[11px] font-semibold" :class="route().current('home') ? 'text-forest font-bold' : 'text-gray-500'">
                <GenreLogo size="sm" :light="false" :showText="false" class="scale-90" />
                <span class="mt-0.5">Beranda</span>
            </Link>
            <Link :href="route('academy.index')" class="flex flex-col items-center py-1 px-3 text-[11px] font-semibold" :class="$page.url.startsWith('/academy') ? 'text-forest font-bold' : 'text-gray-500'">
                <BookOpen class="w-5 h-5 mb-0.5" />
                <span>Belajar</span>
            </Link>
            <Link :href="route('lifequest.index')" class="flex flex-col items-center py-1 px-3 text-[11px] font-semibold relative" :class="$page.url.startsWith('/lifequest') ? 'text-forest font-bold' : 'text-gray-500'">
                <div class="w-9 h-9 rounded-full bg-sunshine text-forest flex items-center justify-center -mt-4 shadow-md border-2 border-white">
                    <Compass class="w-5 h-5" />
                </div>
                <span class="mt-0.5 font-bold text-forest">LifeQuest</span>
            </Link>
            <Link :href="route('campaigns.index')" class="flex flex-col items-center py-1 px-3 text-[11px] font-semibold" :class="$page.url.startsWith('/advocacy') ? 'text-forest font-bold' : 'text-gray-500'">
                <Megaphone class="w-5 h-5 mb-0.5" />
                <span>Aksara Berdaya</span>
            </Link>
            <Link :href="auth?.user ? (auth.user.is_admin ? route('admin.dashboard') : route('dashboard')) : route('login')" class="flex flex-col items-center py-1 px-3 text-[11px] font-semibold" :class="route().current('dashboard') || route().current('admin.dashboard') ? 'text-forest font-bold' : 'text-gray-500'">
                <UserIcon class="w-5 h-5 mb-0.5" />
                <span>{{ auth?.user ? 'Profil' : 'Masuk' }}</span>
            </Link>
        </nav>
    </div>
</template>
