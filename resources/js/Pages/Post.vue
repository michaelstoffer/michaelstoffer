<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JsonLd from '@/Components/JsonLd.vue'

const props = defineProps({ post: { type: Object, required: true } })

const ld = {
    '@context':'https://schema.org', '@type':'Article',
    headline: props.post.title,
    description: props.post.excerpt,
    image: props.post.cover || '/og.jpg',
    author: { '@type':'Person', name:'Michael Stoffer' },
    datePublished: props.post.published_at,
    dateModified: props.post.updated_at || props.post.published_at,
    mainEntityOfPage: typeof window !== 'undefined' ? `${window.location.origin}/blog/${props.post.slug}` : ''
}
</script>

<template>
    <AppLayout>
        <SeoHead :title="post.title + ' — Blog'" :description="post.excerpt" :image="post.cover" type="article" :published-time="post.published_at" :modified-time="post.updated_at" />
        <JsonLd :data="ld" />

        <section class="pt-10 sm:pt-14">
            <nav class="text-sm text-slate-600"><a href="/blog" class="hover:underline">← Back to Blog</a></nav>
        </section>

        <article class="mt-4 max-w-3xl">
            <header>
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">{{ post.title }}</h1>
                <p class="mt-2 text-xs text-slate-500">
                    <time :datetime="post.published_at">{{ new Date(post.published_at).toLocaleDateString() }}</time>
                    <span v-if="post.updated_at && post.updated_at !== post.published_at"> • Updated <time :datetime="post.updated_at">{{ new Date(post.updated_at).toLocaleDateString() }}</time></span>
                </p>
                <img v-if="post.cover" :src="post.cover" :alt="post.title + ' cover'" class="mt-4 w-full rounded-xl border border-slate-200 aspect-[16/9] object-cover" loading="lazy" />
            </header>

            <div class="prose prose-slate mt-6 max-w-none" v-html="post.html"></div>
        </article>
    </AppLayout>
</template>
