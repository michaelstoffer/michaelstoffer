<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps({ title: String, desc: String, href: String })
</script>
<template>
    <Link :href="href" class="group block rounded-2xl bg-white border border-slate-200 p-6 hover:shadow-lg transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand.accent">
        <h3 class="text-lg font-semibold text-slate-900">{{ title }}</h3>
        <p class="mt-2 text-slate-600">{{ desc }}</p>
        <div class="mt-4 inline-flex items-center text-sm font-medium text-slate-900 group-hover:underline">
            <span>Explore</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 ml-1"><path d="M5 12h12m0 0-4-4m4 4-4 4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
    </Link>
</template>
