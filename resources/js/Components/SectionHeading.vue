<script setup>
const props = defineProps({ eyebrow: String, title: String, sub: String, linkHref: String, linkText: String })
</script>
<template>
    <div class="flex items-end justify-between gap-4">
        <div>
            <p v-if="eyebrow" class="text-xs uppercase tracking-wider font-medium text-amber-600">{{ eyebrow }}</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-semibold text-slate-900">{{ title }}</h2>
            <p v-if="sub" class="mt-2 text-slate-600">{{ sub }}</p>
        </div>
        <a v-if="linkHref && linkText" :href="linkHref" class="hidden sm:inline-flex items-center text-sm font-medium text-slate-900 hover:underline">{{ linkText }}</a>
    </div>
</template>
