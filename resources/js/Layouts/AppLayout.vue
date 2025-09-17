<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import GlobalSchema from '@/Components/GlobalSchema.vue'

// Optional site-wide props (location, email, social)
const props = defineProps({
    site: {
        type: Object,
        default: () => ({
            name: 'Michael Stoffer',
            tagline: "Engineer • Songwriter • Photographer",
            location: 'Myrtle Beach, SC',
            email: 'mstoffer@michaelstoffer.com',
            socials: { x: 'https://x.com/michaelstoffer', github: 'https://github.com/michaelstoffer', linkedin: 'https://www.linkedin.com/in/michaelstoffer/' }
        })
    }
})

const mobileOpen = ref(false)
const nav = [
    { name: 'Software', href: '/software' },
    { name: "Music", href: '/music' },
    { name: 'Photography', href: '/photography' },
    { name: 'Blog', href: '/blog' },
    { name: 'About', href: '/about' },
    { name: 'Contact', href: '/contact' },
]
</script>

<template>
    <div class="min-h-screen bg-paper">
        <!-- Skip link for a11y -->
        <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-white text-slate-900 px-3 py-2 rounded">Skip to content</a>

        <!-- Header / Primary Nav -->
        <header class="sticky top-0 z-40 bg-paper/80 backdrop-blur border-b border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link href="/" class="font-semibold text-lg tracking-tight">{{ site.name }}</Link>
                    <span class="hidden sm:inline text-slate-400">—</span>
                    <span class="hidden sm:inline text-sm text-slate-500">{{ site.tagline }}</span>
                </div>

                <nav class="hidden md:flex items-center gap-6 text-sm">
                    <Link v-for="item in nav" :key="item.name" :href="item.href" class="text-slate-700 hover:text-slate-900">{{ item.name }}</Link>
                </nav>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-300">
                    <span class="sr-only">Toggle menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 fill-current"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div v-show="mobileOpen" class="md:hidden border-t border-slate-200">
                <div class="px-4 py-3 space-y-2">
                    <Link v-for="item in nav" :key="item.name" :href="item.href" class="block px-2 py-2 rounded hover:bg-slate-100">{{ item.name }}</Link>
                </div>
            </div>
        </header>

        <!-- Main slot -->
        <main id="main" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-24 border-t border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 text-sm text-slate-500 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="font-medium text-slate-700">{{ site.name }}</p>
                    <p class="mt-1">{{ site.tagline }}</p>
                    <p class="mt-1">Based in <span class="font-medium">{{ site.location }}</span></p>
                </div>
                <nav class="flex flex-wrap gap-4">
                    <Link href="/contact" class="hover:text-slate-700">Contact</Link>
                    <Link href="/about" class="hover:text-slate-700">About</Link>
                    <Link href="/blog" class="hover:text-slate-700">Blog</Link>
                    <a v-if="site.socials.github" :href="site.socials.github" class="hover:text-slate-700" target="_blank" rel="noreferrer">GitHub</a>
                    <a v-if="site.socials.linkedin" :href="site.socials.linkedin" class="hover:text-slate-700" target="_blank" rel="noreferrer">LinkedIn</a>
                    <a v-if="site.socials.x" :href="site.socials.x" class="hover:text-slate-700" target="_blank" rel="noreferrer">X</a>
                </nav>
            </div>
        </footer>

        <GlobalSchema />
    </div>
</template>
