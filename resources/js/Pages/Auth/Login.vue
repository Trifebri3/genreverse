<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GenreLogo from '@/Components/GenreLogo.vue';
import { 
    Mail, 
    Lock, 
    Eye, 
    EyeOff, 
    ArrowRight, 
    Sparkles, 
    ShieldCheck, 
    ArrowLeft,
    CheckCircle2,
    Newspaper,
    FolderArchive,
    ImagePlus,
    HeartHandshake
} from 'lucide-vue-next';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: true,
});

const fillAdminCredentials = () => {
    form.email = 'kingnasar@siyota.org';
    form.password = 'Kingnasar';
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50 font-sans selection:bg-[#2166F3] selection:text-white">
        <Head title="Masuk ke Panel Administrator - GENREVERSE" />

        <!-- LEFT HERO PANEL (DESKTOP) -->
        <div class="lg:w-5/12 bg-gradient-to-br from-[#0B1528] via-[#102447] to-[#16386D] text-white p-8 sm:p-12 lg:p-16 flex flex-col justify-between relative overflow-hidden shrink-0">
            <!-- Background Ambient Glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#2166F3]/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Logo & Tagline -->
            <div class="relative z-10 space-y-6">
                <Link :href="route('home')" class="inline-block transition hover:opacity-90">
                    <GenreLogo size="lg" :light="true" />
                </Link>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-amber-300 backdrop-blur-md">
                    <ShieldCheck class="w-4 h-4 text-amber-300" />
                    <span>Portal Administrator Publikasi</span>
                </div>
            </div>

            <!-- Center Narrative -->
            <div class="relative z-10 my-10 space-y-6">
                <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight tracking-tight">
                    Satu Semesta, <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-sky-200 to-amber-200">
                        Kendali Penuh Publikasi.
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/80 leading-relaxed max-w-md">
                    Kelola warta berita nasional, arsip laporan dan regulasi resmi, galeri dokumentasi kegiatan, serta kurasi suara dan harapan remaja se-Indonesia dalam satu panel terpadu.
                </p>

                <!-- 4 Publication Badges -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-2.5 backdrop-blur-xs">
                        <Newspaper class="w-4 h-4 text-sky-300 shrink-0" />
                        <span class="text-xs font-bold text-white">Berita & Warta</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-2.5 backdrop-blur-xs">
                        <FolderArchive class="w-4 h-4 text-teal-300 shrink-0" />
                        <span class="text-xs font-bold text-white">Dokumen & Laporan</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-2.5 backdrop-blur-xs">
                        <ImagePlus class="w-4 h-4 text-amber-300 shrink-0" />
                        <span class="text-xs font-bold text-white">Galeri Kegiatan</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-2.5 backdrop-blur-xs">
                        <HeartHandshake class="w-4 h-4 text-rose-300 shrink-0" />
                        <span class="text-xs font-bold text-white">Ucapan & Harapan</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Powered By Logos -->
            <div class="relative z-10 pt-6 border-t border-white/10 space-y-3">
                <div class="text-[10px] font-black uppercase tracking-widest text-blue-200/60">
                    POWERED BY FOUNDATION PARTNERS
                </div>
                <div class="flex items-center gap-6">
                    <a href="https://community.siyota.org" target="_blank" rel="noopener noreferrer" class="hover:opacity-100 opacity-80 transition">
                        <img 
                            src="https://community.siyota.org/csyota.webp" 
                            alt="The Community Space YOTA" 
                            class="h-7 w-auto object-contain brightness-0 invert"
                            @error="($event.target as HTMLImageElement).src = '/images/csyota.webp'"
                        />
                    </a>
                    <div class="h-5 w-[1px] bg-white/20"></div>
                    <a href="https://siyota.org" target="_blank" rel="noopener noreferrer" class="hover:opacity-100 opacity-80 transition">
                        <img 
                            src="https://siyota.org/image/logo.png" 
                            alt="Yota Adiwidya Center" 
                            class="h-8 w-auto object-contain brightness-0 invert"
                            @error="($event.target as HTMLImageElement).src = '/images/yota_logo.png'"
                        />
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT LOGIN FORM PANEL -->
        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-20 bg-white">
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo (shown only on mobile) -->
                <div class="lg:hidden flex items-center justify-between pb-6 border-b border-slate-100">
                    <GenreLogo size="md" />
                    <Link :href="route('home')" class="text-xs font-bold text-slate-500 hover:text-[#2166F3] flex items-center gap-1">
                        <ArrowLeft class="w-3.5 h-3.5" />
                        <span>Website</span>
                    </Link>
                </div>

                <!-- Form Header -->
                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-black text-[#101D35] tracking-tight">
                        Masuk ke Panel Kendali
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Gunakan akun administrator utama untuk mengelola seluruh konten dan publikasi GENREVERSE.
                    </p>
                </div>

                <!-- Quick Autofill Helper for King Nasar -->
                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <div class="text-[11px] font-bold text-[#2166F3] flex items-center gap-1.5">
                            <Sparkles class="w-3.5 h-3.5 text-amber-500" />
                            <span>Akun Administrator Utama</span>
                        </div>
                        <div class="text-xs text-slate-600 font-medium font-mono">
                            kingnasar@siyota.org
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="fillAdminCredentials"
                        class="px-3 py-1.5 rounded-xl bg-[#2166F3] hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs shrink-0"
                    >
                        Isi Otomatis
                    </button>
                </div>

                <!-- Status alert -->
                <div v-if="status" class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700">
                    {{ status }}
                </div>

                <!-- Form Inputs -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Email Administrator *
                        </label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@siyota.org"
                                class="w-full pl-10 pr-4 py-3 rounded-2xl text-xs sm:text-sm border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3] transition"
                                :class="{ 'border-rose-300 ring-rose-200': form.errors.email }"
                            />
                        </div>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-rose-600 font-medium">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                Kata Sandi *
                            </label>
                        </div>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi..."
                                class="w-full pl-10 pr-11 py-3 rounded-2xl text-xs sm:text-sm border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2166F3] focus:border-[#2166F3] transition"
                                :class="{ 'border-rose-300 ring-rose-200': form.errors.password }"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1"
                                :title="showPassword ? 'Sembunyikan sandi' : 'Tampilkan sandi'"
                            >
                                <component :is="showPassword ? EyeOff : Eye" class="w-4 h-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-rose-600 font-medium">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="rounded border-slate-300 text-[#2166F3] focus:ring-[#2166F3]"
                            />
                            <span class="text-xs font-semibold text-slate-600">Ingat sesi login saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 px-6 rounded-2xl bg-[#2166F3] hover:bg-blue-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2"
                        :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
                    >
                        <span>{{ form.processing ? 'Memverifikasi...' : 'Masuk ke Panel Kendali' }}</span>
                        <ArrowRight class="w-4 h-4" />
                    </button>
                </form>

                <!-- Back to Homepage -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <Link :href="route('home')" class="hover:text-[#2166F3] font-bold flex items-center gap-1.5 transition">
                        <ArrowLeft class="w-3.5 h-3.5" />
                        <span>Kembali ke Website Publik</span>
                    </Link>
                    <span>GENREVERSE v1.0</span>
                </div>
            </div>
        </div>
    </div>
</template>
