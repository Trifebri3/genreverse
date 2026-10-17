<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { 
    Calendar, 
    Eye, 
    User, 
    ArrowLeft, 
    Share2, 
    Tag, 
    Sparkles,
    ArrowRight
} from 'lucide-vue-next';

defineProps<{
    article: {
        id: number;
        title: string;
        slug: string;
        category: string;
        excerpt: string;
        content: string;
        cover_image?: string;
        author_name?: string;
        views_count: number;
        created_at: string;
    };
    relatedArticles: any[];
}>();

const shareArticle = () => {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href,
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan artikel berhasil disalin!');
    }
};
</script>

<template>
    <PublicLayout>
        <Head :title="`${article.title} - GENREVERSE`" />

        <div class="max-w-4xl mx-auto space-y-8 pb-16">
            <!-- Breadcrumbs / Back button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('articles.index')"
                    class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-forest transition"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span>Kembali ke Warta & Publikasi</span>
                </Link>

                <button
                    @click="shareArticle"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition"
                >
                    <Share2 class="w-3.5 h-3.5 text-forest" />
                    <span>Bagikan</span>
                </button>
            </div>

            <!-- Header Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                        {{ article.category }}
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        <Eye class="w-3.5 h-3.5" />
                        <span>{{ article.views_count }} dibaca</span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-black text-gray-900 leading-tight">
                    {{ article.title }}
                </h1>

                <p class="text-sm sm:text-base text-gray-600 font-medium leading-relaxed border-l-4 border-forest pl-4 italic">
                    {{ article.excerpt }}
                </p>

                <!-- Author info bar -->
                <div class="pt-4 pb-2 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-forest text-white flex items-center justify-center font-bold text-sm">
                        <User class="w-5 h-5" />
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900">{{ article.author_name || 'Humas GenRe Indonesia' }}</div>
                        <div class="text-[11px] text-gray-400">Tim Publikasi Digital GENREVERSE</div>
                    </div>
                </div>
            </div>

            <!-- Big Cover Image -->
            <div v-if="article.cover_image" class="w-full h-80 sm:h-96 rounded-3xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                <img :src="article.cover_image" :alt="article.title" class="w-full h-full object-cover" />
            </div>

            <!-- Article HTML Body -->
            <article 
                class="prose prose-sm sm:prose-base max-w-none text-gray-800 leading-relaxed space-y-4 font-normal"
                v-html="article.content"
            ></article>

            <!-- Bottom Share CTA -->
            <div class="p-6 rounded-2xl bg-forest-50 border border-forest-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center sm:text-left">
                    <h4 class="text-sm font-black text-forest">Suka dengan artikel ini?</h4>
                    <p class="text-xs text-forest-700">Ayo bagikan pesan positif dan inspirasi ini kepada teman sebayamu!</p>
                </div>
                <button
                    @click="shareArticle"
                    class="px-5 py-2.5 rounded-xl bg-forest text-white font-bold text-xs hover:bg-forest-700 shadow-sm transition flex items-center gap-2"
                >
                    <Share2 class="w-4 h-4" />
                    <span>Bagikan Artikel</span>
                </button>
            </div>

            <!-- Related Articles -->
            <div v-if="relatedArticles.length > 0" class="pt-8 space-y-6">
                <h3 class="text-lg font-black text-gray-900">Artikel Terkait Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div
                        v-for="rel in relatedArticles"
                        :key="rel.id"
                        class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group"
                    >
                        <div class="h-36 bg-gray-100 overflow-hidden">
                            <img 
                                v-if="rel.cover_image" 
                                :src="rel.cover_image" 
                                :alt="rel.title" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            />
                        </div>
                        <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold text-teal uppercase">{{ rel.category }}</span>
                                <h4 class="font-bold text-xs text-gray-900 line-clamp-2 mt-1 group-hover:text-forest transition">
                                    <Link :href="route('articles.show', rel.slug)">{{ rel.title }}</Link>
                                </h4>
                            </div>
                            <Link 
                                :href="route('articles.show', rel.slug)" 
                                class="text-[11px] font-bold text-forest inline-flex items-center gap-1 mt-2"
                            >
                                <span>Baca</span>
                                <ArrowRight class="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
