<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    slug: { type: String },
    case: { type: Object },
})
</script>

<template>
    <AppLayout>
        <SeoHead :title="`${props.case.study} - Case Study`" :description="props.case.summary || props.case.result" :url="`https://www.michaelstoffer.com/software/${slug}`" />

        <section class="pt-12 sm:pt-16 pb-8 max-w-3xl">
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
        </section>
    </AppLayout>
</template>
