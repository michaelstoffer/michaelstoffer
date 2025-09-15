<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import SongCard from '@/Components/SongCard.vue'
import { computed, ref } from 'vue'

const props = defineProps({ songs: { type: Array, default: () => [] } })

const q = ref('')
const activeTag = ref('')

const allTags = computed(() => {
    const set = new Set()
    props.songs.forEach(s => (s.tags || []).forEach(t => set.add(t)))
    return Array.from(set).sort()
})

const filtered = computed(() => {
    const term = q.value.trim().toLowerCase()
    return props.songs.filter(s => {
        const matchesTerm = !term ||
            s.title.toLowerCase().includes(term) ||
            (s.description || '').toLowerCase().includes(term)
        const matchesTag = !activeTag.value || (s.tags || []).includes(activeTag.value)
        return matchesTerm && matchesTag
    })
})

function clearFilters() {
    q.value = ''
    activeTag.value = ''
}
</script>

<template>
    <AppLayout>
        <SeoHead title="Music — Michael Stoffer" description="Songs with lyric sheets and visual kits." />

        <section class="pt-12 sm:pt-16 pb-6">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Music</h1>
                <p class="mt-4 text-lg text-slate-700">Fun songs with lyric sheets and classroom-friendly visuals.</p>
            </div>
        </section>

        <!-- Filters -->
        <section class="pb-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <label class="flex-1">
                    <span class="sr-only">Search songs</span>
                    <input v-model="q" type="search" placeholder="Search songs…" class="w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0 pl-4" />
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
            <SectionHeading eyebrow="Songs" title="Listen & learn" sub="Click any song for lyrics and downloads." />
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <SongCard v-for="s in filtered" :key="s.slug" :song="s" />
            </div>
            <p v-if="!filtered.length" class="mt-6 text-slate-600">No songs match that search yet.</p>
        </section>
    </AppLayout>
</template>
