<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import GalleryCard from '@/Components/GalleryCard.vue'
import { ref, computed } from 'vue'

const props = defineProps({ galleries: { type: Array, default: () => [] } })

const q = ref('')
const activeTag = ref('')

const allTags = computed(() => {
    const set = new Set()
    props.galleries.forEach(g => (g.tags || []).forEach(t => set.add(t)))
    return Array.from(set).sort()
})

const filtered = computed(() => {
    const term = q.value.trim().toLowerCase()
    return props.galleries.filter(g => {
        const matchesTerm = !term || g.title.toLowerCase().includes(term) || (g.description || '').toLowerCase().includes(term)
        const matchesTag = !activeTag.value || (g.tags || []).includes(activeTag.value)
        return matchesTerm && matchesTag
    })
})

function clearFilters(){ q.value=''; activeTag.value='' }
</script>

<template>
    <AppLayout>
        <SeoHead title="Photography — Michael Stoffer" description="Curated galleries with clean, honest images." />

        <section class="pt-12 sm:pt-16 pb-6">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Photography</h1>
                <p class="mt-4 text-lg text-slate-700">Curated, honest images—simple sets, strong lines, and timeless light.</p>
            </div>
        </section>

        <!-- Filters -->
        <section class="pb-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <label class="flex-1">
                    <span class="sr-only">Search galleries</span>
                    <input v-model="q" type="search" placeholder="Search galleries…" class="w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0" />
                </label>
                <div class="flex flex-wrap gap-2 items-center">
                    <button @click="activeTag = ''" :class="['px-3 py-1.5 rounded-full text-sm border', activeTag === '' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-900 border-slate-300']">All</button>
                    <button v-for="t in allTags" :key="t" @click="activeTag = t" :class="['px-3 py-1.5 rounded-full text-sm border', activeTag === t ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-900 border-slate-300']">{{ t }}</button>
                    <button v-if="q || activeTag" @click="clearFilters" class="px-3 py-1.5 rounded-full text-sm border border-slate-300">Clear</button>
                </div>
            </div>
        </section>

        <!-- Grid -->
        <section class="pb-12 sm:pb-16">
            <SectionHeading eyebrow="Galleries" title="Selected work" sub="Click any gallery to view." />
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <GalleryCard v-for="g in filtered" :key="g.slug" :gallery="g" />
            </div>
            <p v-if="!filtered.length" class="mt-6 text-slate-600">No galleries match that search.</p>
        </section>
    </AppLayout>
</template>
