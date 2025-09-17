<script setup>
import { Head } from '@inertiajs/vue3'
const props = defineProps({ data: [Object, Array] })
</script>
<template>
    <Head><script type="application/ld+json">{{ JSON.stringify(props.data) }}</script></Head>
</template>
