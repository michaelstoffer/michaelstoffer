<script setup>
const props = defineProps({ tone: { type: String, default: 'slate' } })
</script>
<template>
  <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs text-slate-700 border-slate-300 bg-white">
    <slot />
  </span>
</template>
