<script setup>
import Badge from '@/Components/Badge.vue'
const props = defineProps({
    title: String,
    summary: String,
    href: String,
    problem: String,
    approach: String,
    result: String,
    tags: { type: Array, default: () => [] },
})
</script>
<template>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-lg transition-shadow">
        <h3 class="text-lg font-semibold text-slate-900"><a :href="href" class="hover:underline">{{ title }}</a></h3>
        <p class="mt-2 text-slate-600">{{ summary }}</p>

        <dl class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <dt class="font-medium text-slate-900">Problem</dt>
                <dd class="mt-1 text-slate-600">{{ problem }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-900">Approach</dt>
                <dd class="mt-1 text-slate-600">{{ approach }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-900">Result</dt>
                <dd class="mt-1 text-slate-600">{{ result }}</dd>
            </div>
        </dl>

        <div class="mt-4 flex flex-wrap gap-2">
            <Badge v-for="t in tags" :key="t">{{ t }}</Badge>
        </div>
    </div>
</template>
