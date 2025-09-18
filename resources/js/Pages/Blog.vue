<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import BlogCard from "../Components/BlogCard.vue"
import {computed, ref} from "vue"
import SectionHeading from "../Components/SectionHeading.vue"

const props = defineProps({ posts: { type: Array, default: () => [] } })

const q = ref('')
const activeTag = ref('')

const allTags = computed(() => {
    const set = new Set()
    props.posts.forEach(p => (p.tags || []).forEach(t => set.add(t)))
    return Array.from(set).sort()
})

const filtered = computed(() => {
    const term = q.value.trim().toLowerCase()
    return props.posts.filter(p => {
        const matchesTerm = !term ||
            p.title.toLowerCase().includes(term) ||
            (p.description || '').toLowerCase().includes(term)
        const matchesTag = !activeTag.value || (p.tags || []).includes(activeTag.value)
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
        <SeoHead title="Blog — Michael Stoffer" description="Practical notes on building, learning, and shipping." />

        <section class="pt-12 sm:pt-16 pb-6">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Blog</h1>
                <p class="mt-4 text-lg text-slate-700">Short, useful posts about engineering and creative work.</p>
            </div>
        </section>

        <!-- Filters -->
        <section class="pb-6">
            <div class="flex flex-col md:flex-row gap-3 md:items-center">
                <label class="flex-1">
                    <span class="sr-only">Search posts</span>
                    <input v-model="q" type="search" placeholder="Search posts…" class="w-full pt-1 pb-1 rounded-lg bg-slate-200 border-slate-300 focus:border-slate-400 focus:ring-0 pl-4" />
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
            <SectionHeading eyebrow="Posts" title="Read & learn" sub="Click any post for more details." />
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <BlogCard v-for="p in filtered" :key="p.slug" :post="p" />
            </div>
            <p v-if="!filtered.length" class="mt-6 text-slate-600">No posts match that search yet.</p>
        </section>
    </AppLayout>
</template>
