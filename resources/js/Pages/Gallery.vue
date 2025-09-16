<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Lightbox from '@/Components/Lightbox.vue'
import { ref } from 'vue'

const props = defineProps({
    slug: { type: String, required: true },
    gallery: { type: Object, required: true },
    related: { type: Array, default: () => [] },
})

const open = ref(false)
const index = ref(0)

function show(i){ index.value = i; open.value = true }
</script>

<template>
    <AppLayout>
        <SeoHead :title="`${gallery.title} — Photography`" :description="gallery.description" :url="`https://www.michaelstoffer.com/photography/${slug}`" :image="gallery.cover" />

        <section class="pt-10 sm:pt-14">
            <nav class="text-sm text-slate-600"><a href="/photography" class="hover:underline">← Back to Photography</a></nav>
        </section>

        <header class="mt-4 max-w-3xl">
            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">{{ gallery.title }}</h1>
            <p v-if="gallery.description" class="mt-3 text-slate-700">{{ gallery.description }}</p>
        </header>

        <!-- Grid -->
        <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <button v-for="(p,i) in gallery.photos" :key="p.src" @click="show(i)" class="group relative rounded-xl overflow-hidden border border-slate-200 bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand.accent">
                <img :src="p.src" :alt="p.alt || `${gallery.title} photo ${i+1}`" :width="p.w" :height="p.h" class="w-full h-auto object-cover group-hover:opacity-95" loading="lazy" />
            </button>
        </section>

        <!-- Lightbox -->
        <Lightbox v-if="open" :open.sync="open" :items="gallery.photos" v-model:index="index" />

        <!-- Related -->
        <section v-if="related?.length" class="mt-12 sm:mt-16">
            <h2 class="text-xl font-semibold text-slate-900">More galleries</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a v-for="r in related" :key="r.slug" :href="`/photography/${r.slug}`" class="block rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg">
                    <img v-if="r.cover" :src="r.cover" :alt="`${r.title} cover`" class="w-full aspect-[4/3] object-cover" loading="lazy" />
                    <div class="p-4">
                        <h3 class="text-slate-900 font-medium">{{ r.title }}</h3>
                    </div>
                </a>
            </div>
        </section>
    </AppLayout>
</template>
