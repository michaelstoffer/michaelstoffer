<script setup>
import { Head, usePage } from '@inertiajs/vue3'

const props = defineProps({
    title: { type: String, default: 'Michael Stoffer' },
    description: { type: String, default: 'Engineer, songwriter, photographer—building things that last.' },
    url: { type: String, default: '' },
    image: { type: String, default: '/og.jpg' },
    noindex: { type: Boolean, default: false },
})

const page = usePage()
const site = page.props.site || { url: 'https://www.michaelstoffer.com', name: 'Michael Stoffer' }
const canonical = props.url || (site.url + (typeof window !== 'undefined' ? window.location.pathname : ''))
const robots = props.noindex ? 'noindex, nofollow' : 'index, follow'
</script>

<template>
    <Head :title="props.title">
        <meta name="description" :content="props.description" />
        <meta name="robots" :content="robots" />
        <link rel="canonical" :href="canonical" />

        <!-- Open Graph -->
        <meta property="og:title" :content="props.title" />
        <meta property="og:description" :content="props.description" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="canonical" />
        <meta property="og:image" :content="props.image" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="props.title" />
        <meta name="twitter:description" :content="props.description" />
        <meta name="twitter:image" :content="props.image" />
    </Head>
</template>
