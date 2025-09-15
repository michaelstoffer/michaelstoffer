<script setup>
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    title: { type: String, default: 'Michael Stoffer' },
    description: { type: String, default: "Engineer, children’s songwriter, photographer—building things that last." },
    url: { type: String, default: 'https://www.michaelstoffer.com' },
    image: { type: String, default: '' },
})
</script>

<template>
    <Head :title="title">
        <meta name="description" :content="description" />
        <meta property="og:title" :content="title" />
        <meta property="og:description" :content="description" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="url" />
        <meta v-if="image" property="og:image" :content="image" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="title" />
        <meta name="twitter:description" :content="description" />
        <meta v-if="image" name="twitter:image" :content="image" />
        <link rel="canonical" :href="url" />
    </Head>
</template>
