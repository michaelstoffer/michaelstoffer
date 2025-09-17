<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SongCard from '@/Components/SongCard.vue'
import { marked } from 'marked'
import {computed} from "vue";

const props = defineProps({
    slug: { type: String, required: true },
    song: { type: Object, required: true },
    related: { type: Array, default: () => [] },
})

const renderedBody = computed(() => {
    return props.song.body ? marked.parse(props.song.body) : ''
})
</script>

<template>
    <AppLayout>
        <SeoHead :title="`${song.title} — Children’s Music`" :description="song.description" :url="`https://www.michaelstoffer.com/music/${slug}`" :image="song.cover" />

        <section class="pt-10 sm:pt-14">
            <nav class="text-sm text-slate-600"><a href="/music" class="hover:underline">← Back to Music</a></nav>
        </section>

        <section class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cover + Audio -->
            <aside class="lg:col-span-1">
                <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                    <img v-if="song.cover" :src="song.cover" :alt="song.title" class="w-full aspect-square object-cover" loading="lazy" />
                    <div class="p-4">
                        <audio v-if="song.audioSrc" :src="song.audioSrc" controls class="w-full"></audio>
                        <p class="mt-3 text-sm text-slate-600" v-if="song.duration">
                            <span class="font-medium text-slate-900">Duration:</span> {{ song.duration }}
                        </p>
                        <p class="mt-1 text-sm text-slate-600" v-if="song.key || song.bpm">
                            <span class="font-medium text-slate-900">Key/BPM:</span>
                            <span>{{ song.key || '—' }}</span>
                            <span v-if="song.bpm"> • {{ song.bpm }} BPM</span>
                        </p>
                    </div>
                </div>

                <!-- Downloads -->
                <div v-if="song.downloads?.length" class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
                    <h3 class="text-sm font-semibold text-slate-900">Downloads</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li v-for="d in song.downloads" :key="d.href">
                            <a :href="d.href" class="hover:underline">{{ d.label }}</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Main -->
            <article class="lg:col-span-2">
                <header>
                    <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">{{ song.title }}</h1>
                    <p v-if="song.subtitle" class="mt-1 text-slate-500">{{ song.subtitle }}</p>
                    <p v-if="song.description" class="mt-4 text-lg text-slate-700">{{ song.description }}</p>
                    <div v-if="renderedBody" class="mt-4 text-slate-700 markdown-body" v-html="renderedBody" />
                </header>

                <!-- Video -->
                <div v-if="song.videoEmbed" class="mt-6 aspect-video rounded-2xl overflow-hidden border border-slate-200 bg-white">
                    <iframe :src="song.videoEmbed" title="{{ song.title }} video" allowfullscreen class="w-full h-full"></iframe>
                </div>

                <!-- Lyrics -->
                <section v-if="song.lyrics" class="mt-8">
                    <h2 class="text-xl font-semibold text-slate-900">Lyrics</h2>
                    <pre class="mt-3 whitespace-pre-wrap text-slate-700">{{ song.lyrics }}</pre>
                </section>

                <!-- Tags -->
                <section v-if="song.tags?.length" class="mt-8">
                    <div class="flex flex-wrap gap-2">
                        <span v-for="t in song.tags" :key="t" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-700">{{ t }}</span>
                    </div>
                </section>
            </article>
        </section>

        <!-- Related -->
        <section v-if="related?.length" class="mt-12 sm:mt-16">
            <h2 class="text-xl font-semibold text-slate-900">You might also like</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <SongCard v-for="r in related" :key="r.slug" :song="r" />
            </div>
        </section>
    </AppLayout>
</template>

<style lang="scss" scoped>
.markdown-body {
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
        background-color: #f3f4f6; /* Tailwind's slate-100 */
        padding: 1em;
        border-radius: 0.5em;
        overflow-x: auto;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    :deep(code) {
        background-color: #f3f4f6; /* Tailwind's slate-100 */
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
