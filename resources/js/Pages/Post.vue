<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JsonLd from '@/Components/JsonLd.vue'
import { marked } from 'marked'
import {computed} from "vue"

const props = defineProps({ post: { type: Object, required: true } })

const renderedHtml = computed(() => {
    return props.post.html ? marked.parse(props.post.html) : ''
})

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

                <!-- Tags -->
                <section v-if="post.tags?.length" class="mt-2">
                    <div class="flex flex-wrap gap-2">
                        <span v-for="t in post.tags" :key="t" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-700">{{ t }}</span>
                    </div>
                </section>
                <img v-if="post.cover" :src="post.cover" :alt="post.title + ' cover'" class="mt-4 w-full rounded-xl border border-slate-200 aspect-[16/9] object-cover" loading="lazy" />
            </header>

            <div class="prose prose-slate mt-6 max-w-none post-content" v-html="renderedHtml"></div>
        </article>
    </AppLayout>
</template>

<style lang="scss" scoped>
.post-content {
    :deep(p) {
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(h1) {
        font-size: 2em;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
        font-weight: bold;
    }

    :deep(h2) {
        font-size: 1.5em;
        margin-top: 0.83em;
        margin-bottom: 0.83em;
        font-weight: bold;
    }

    :deep(h3) {
        font-size: 1.25em;
        margin-top: 1em;
        margin-bottom: 1em;
        font-weight: bold;
    }

    :deep(h4) {
        font-size: 1em;
        margin-top: 1.33em;
        margin-bottom: 1.33em;
        font-weight: bold;
    }

    :deep(h5) {
        font-size: 0.875em;
        margin-top: 1.67em;
        margin-bottom: 1.67em;
        font-weight: bold;
    }

    :deep(h6) {
        font-size: 0.85em;
        margin-top: 2.33em;
        margin-bottom: 2.33em;
        font-weight: bold;
    }

    :deep(ul) {
        list-style-type: disc;
        padding-left: 1.5em;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(ol) {
        list-style-type: decimal;
        padding-left: 1.5em;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(pre) {
        background-color: #333; /* Tailwind's slate-100 */
        color: #fff;
        padding: 1em;
        border-radius: 0.5em;
        overflow-x: auto;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(code) {
        background-color: #333; /* Tailwind's slate-100 */
        color: #fff;
        padding: 0.2em 0.4em;
        border-radius: 0.25em;
    }

    :deep(a) {
        color: #1e40af; /* Tailwind's blue-800 */
        text-decoration: underline;
    }

    :deep(a:hover) {
        color: #1e3a8a; /* Tailwind's blue-900 */
    }

    :deep(blockquote) {
        border-left: 4px solid #d1d5db; /* Tailwind's slate-300 */
        padding-left: 1em;
        color: #6b7280; /* Tailwind's slate-500 */
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(img) {
        max-width: 100%;
        height: auto;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(table) {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(th), :deep(td) {
        border: 1px solid #d1d5db; /* Tailwind's slate-300 */
        padding: 0.5em;
        text-align: left;
    }

    :deep(th) {
        background-color: #f9fafb; /* Tailwind's slate-50 */
    }

    :deep(hr) {
        border: none;
        border-top: 1px solid #e5e7eb; /* Tailwind's slate-200 */
        margin: 2em 0;
    }

    :deep(p:first-child) {
        margin-top: 0;
    }

    :deep(p:last-child) {
        margin-bottom: 0;
    }

    :deep(h1:first-child),
    :deep(h2:first-child),
    :deep(h3:first-child),
    :deep(h4:first-child),
    :deep(h5:first-child),
    :deep(h6:first-child) {
        margin-top: 0;
    }

    :deep(h1:last-child),
    :deep(h2:last-child),
    :deep(h3:last-child),
    :deep(h4:last-child),
    :deep(h5:last-child),
    :deep(h6:last-child) {
        margin-bottom: 0;
    }

    :deep(ul:first-child),
    :deep(ol:first-child) {
        margin-top: 0;
    }

    :deep(ul:last-child),
    :deep(ol:last-child) {
        margin-bottom: 0;
    }

    :deep(pre:first-child) {
        margin-top: 0;
    }

    :deep(pre:last-child) {
        margin-bottom: 0;
    }

    :deep(blockquote:first-child) {
        margin-top: 0;
    }

    :deep(blockquote:last-child) {
        margin-bottom: 0;
    }

    :deep(table:first-child) {
        margin-top: 0;
    }

    :deep(table:last-child) {
        margin-bottom: 0;
    }

    :deep(hr:first-child) {
        margin-top: 0;
    }

    :deep(hr:last-child) {
        margin-bottom: 0;
    }
}
</style>
