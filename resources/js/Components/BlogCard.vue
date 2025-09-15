<script setup>
const props = defineProps({ title: String, href: String, date: String, excerpt: String })
</script>
<template>
    <article class="rounded-2xl border border-slate-200 bg-white p-6">
        <h3 class="font-semibold text-slate-900"><a :href="href" class="hover:underline">{{ title }}</a></h3>
        <p class="mt-1 text-xs text-slate-500">{{ date }}</p>
        <p class="mt-2 text-sm text-slate-600">{{ excerpt }}</p>
        <a :href="href" class="mt-3 inline-flex items-center text-sm font-medium hover:underline">Read more</a>
    </article>
</template>
