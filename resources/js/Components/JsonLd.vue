<script setup>
import { Head } from '@inertiajs/vue3'
const props = defineProps({ data: [Object, Array] })
</script>
<template>
    <Head><component is="script" type="application/ld+json">{{ JSON.stringify(props.data) }}</component></Head>
</template>
