<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SongCard from '@/Components/SongCard.vue'

const props = defineProps({
    slug: { type: String, required: true },
    song: { type: Object, required: true },
    related: { type: Array, default: () => [] },
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
