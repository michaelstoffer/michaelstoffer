<script setup>
import { Head, usePage } from '@inertiajs/vue3'
const props = defineProps({ data: [Object, Array] })
const page = usePage()
</script>
<template>
    <Head><component is="script" type="application/ld+json" :nonce="page.props.csp_nonce">{{ JSON.stringify(props.data) }}</component></Head>
</template>
