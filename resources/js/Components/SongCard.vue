<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps({ song: { type: Object, required: true } })
</script>

<template>
    <article class="rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
        <Link :href="`/music/${song.slug}`" class="block">
            <img v-if="song.cover" :src="song.cover" :alt="song.title" class="w-full aspect-video object-cover" loading="lazy" />
            <div class="p-5">
                <h3 class="text-lg font-semibold text-slate-900">{{ song.title }}</h3>
                <p class="mt-1 text-xs text-slate-500">{{ song.duration }}</p>
                <p class="mt-2 text-slate-600 line-clamp-3">{{ song.description }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span v-for="t in song.tags" :key="t" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-700">{{ t }}</span>
                </div>
            </div>
        </Link>
        <div v-if="song.audioSrc" class="px-5 pb-5">
            <audio :src="song.audioSrc" controls class="w-full"></audio>
        </div>
    </article>
</template>
