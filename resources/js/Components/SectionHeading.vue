<script setup>
const props = defineProps({ eyebrow: String, title: String, sub: String })
</script>
<template>
    <div class="max-w-2xl">
        <p v-if="eyebrow" class="text-xs uppercase tracking-wider text-slate-500">{{ eyebrow }}</p>
        <h2 class="mt-1 text-2xl sm:text-3xl font-semibold text-slate-900">{{ title }}</h2>
        <p v-if="sub" class="mt-2 text-slate-600">{{ sub }}</p>
    </div>
</template>
