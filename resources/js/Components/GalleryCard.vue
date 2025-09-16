<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps({ gallery: { type: Object, required: true } })
</script>

<template>
    <article class="rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
        <Link :href="`/photography/${gallery.slug}`" class="block">
            <img v-if="gallery.cover" :src="gallery.cover" :alt="`${gallery.title} cover`" class="w-full aspect-[4/3] object-cover" loading="lazy" />
            <div class="p-5">
                <h3 class="text-lg font-semibold text-slate-900">{{ gallery.title }}</h3>
                <p class="mt-1 text-xs text-slate-500" v-if="gallery.count">{{ gallery.count }} photos</p>
                <p class="mt-2 text-slate-600 line-clamp-3">{{ gallery.description }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span v-for="t in (gallery.tags || [])" :key="t" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-700">{{ t }}</span>
                </div>
            </div>
        </Link>
    </article>
</template>
