<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'
import { marked } from 'marked'

const props = defineProps({
    slug: { type: String },
    case: { type: Object },
})

const renderedBody = computed(() => {
    return props.case.body ? marked.parse(props.case.body) : ''
})
</script>

<template>
    <AppLayout>
        <SeoHead :title="`${props.case.title} - Case Study`" :description="props.case.summary || props.case.result" :url="`https://www.michaelstoffer.com/software/${slug}`" />

        <section class="pt-12 sm:pt-16 pb-8 max-w-3xl">
            <img v-if="props.case.cover" :src="props.case.cover" :alt="`${props.case.title} cover`" class="w-full rounded-2xl border border-slate-200 aspect-video object-cover mb-8" loading="lazy" />
            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">{{ props.case.title }}</h1>
            <p class="mt-3 text-slate-700">{{ props.case.result }}</p>

            <dl class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <dt class="font-medium text-slate-900">Problem</dt>
                    <dd class="mt-1 text-slate-700">{{ props.case.problem }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-900">Approach</dt>
                    <dd class="mt-1 text-slate-700">{{ props.case.approach }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-900">Result</dt>
                    <dd class="mt-1 text-slate-700">{{ props.case.result }}</dd>
                </div>
            </dl>

            <div v-if="props.case.metrics?.length" class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div v-for="m in props.case.metrics" :key="m.label" class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="text-xs uppercase tracking-wide text-slate-500">{{ m.label }}</div>
                    <div class="mt-1 text-xl font-semibold text-slate-900">{{ m.value }}</div>
                </div>
            </div>

            <div v-if="props.case.stack?.length" class="mt-8 text-sm text-slate-700">
                <h3 class="font-medium text-slate-900">Stack</h3>
                <ul class="mt-2 list-disc list-inside">
                    <li v-for="s in props.case.stack" :key="s">{{ s }}</li>
                </ul>
            </div>

            <div v-if="props.case.links?.length" class="mt-8 text-sm">
                <h3 class="font-medium text-slate-900">Links</h3>
                <ul class="mt-2 list-disc list-inside">
                    <li v-for="l in props.case.links" :key="l.href"><a :href="l.href" class="hover:underline">{{ l.label || l.href }}</a></li>
                </ul>
            </div>

            <div v-if="renderedBody" class="prose prose-slate mt-10 max-w-none post-content" v-html="renderedBody"></div>
        </section>
    </AppLayout>
</template>

<style lang="scss" scoped>
.post-content {
    :deep(p) { margin-top: 1em; margin-bottom: 1em; }
    :deep(h2) { font-size: 1.5em; margin-top: 0.83em; margin-bottom: 0.83em; font-weight: bold; }
    :deep(h3) { font-size: 1.25em; margin-top: 1em; margin-bottom: 1em; font-weight: bold; }
    :deep(h4) { font-size: 1em; margin-top: 1.33em; margin-bottom: 1.33em; font-weight: bold; }
    :deep(ul) { list-style-type: disc; padding-left: 1.5em; margin-top: 1em; margin-bottom: 1em; }
    :deep(ol) { list-style-type: decimal; padding-left: 1.5em; margin-top: 1em; margin-bottom: 1em; }
    :deep(pre) { background-color: #333; color: #fff; padding: 1em; border-radius: 0.5em; overflow-x: auto; margin-top: 1em; margin-bottom: 1em; }
    :deep(code) { background-color: #333; color: #fff; padding: 0.2em 0.4em; border-radius: 0.25em; }
    :deep(a) { color: #1e40af; text-decoration: underline; }
    :deep(a:hover) { color: #1e3a8a; }
    :deep(blockquote) { border-left: 4px solid #d1d5db; padding-left: 1em; color: #6b7280; margin-top: 1em; margin-bottom: 1em; }
    :deep(img) { max-width: 100%; height: auto; margin-top: 1em; margin-bottom: 1em; }
    :deep(table) { width: 100%; border-collapse: collapse; margin-top: 1em; margin-bottom: 1em; }
    :deep(th), :deep(td) { border: 1px solid #d1d5db; padding: 0.5em; text-align: left; }
    :deep(th) { background-color: #f9fafb; }
    :deep(hr) { border: none; border-top: 1px solid #e5e7eb; margin: 2em 0; }
}
</style>
