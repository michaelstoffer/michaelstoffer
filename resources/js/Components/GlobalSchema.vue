<script setup>
import { Head, usePage } from '@inertiajs/vue3'
const page = usePage()
const site = page.props.site || { url: 'https://www.michaelstoffer.com', name: 'Michael Stoffer', socials: {} }
const ld = [
    {
        '@context': 'https://schema.org', '@type': 'Person',
        name: 'Michael Stoffer', url: site.url,
        sameAs: Object.values(site.socials || {}).filter(Boolean),
    },
    {
        '@context': 'https://schema.org', '@type': 'WebSite',
        name: 'Michael Stoffer', url: site.url,
    }
]
</script>
<template>
    <Head>
        <script type="application/ld+json">{{ JSON.stringify(ld) }}</script>
    </Head>
</template>
