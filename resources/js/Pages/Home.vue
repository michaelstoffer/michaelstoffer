<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ButtonLink from '@/Components/ButtonLink.vue'
import PillarCard from '@/Components/PillarCard.vue'
import SectionHeading from '@/Components/SectionHeading.vue'

const props = defineProps({ latestPosts: { type: Array, default: () => [] } })
</script>

<template>
    <AppLayout>
        <SeoHead title="Home — Michael Stoffer" description="Engineer, songwriter, photographer—building things that last." />

        <!-- HERO -->
        <section class="pt-16 sm:pt-20 pb-12 sm:pb-16">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight text-slate-900">
                    Engineer, songwriter, photographer—
                    <span class="whitespace-nowrap">building things that last.</span>
                </h1>
                <p class="mt-4 text-lg text-slate-700">
                    I design and ship Laravel/Vue products, create music, and capture clean, honest images. Old‑school craftsmanship, modern tooling.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <ButtonLink href="/software">View Software Work</ButtonLink>
                    <ButtonLink href="/music" variant="outline">Explore Music</ButtonLink>
                    <ButtonLink href="/photography" variant="outline">See Photography</ButtonLink>
                </div>
            </div>
        </section>

        <!-- THREE PILLARS -->
        <section class="py-6 sm:py-10">
            <SectionHeading eyebrow="What I Do" title="Three pillars, one through‑line" sub="Clear, dependable work across software, music, and photography." />
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <PillarCard
                    title="Software Engineering"
                    desc="Laravel + Vue, integrations, internal tools, PDFs & dashboards. Practical, well‑tested builds."
                    href="/software"
                />
                <PillarCard
                    title="Music"
                    desc="Songs, lyric sheets, visual kits, and resources for families and classrooms."
                    href="/music"
                />
                <PillarCard
                    title="Photography"
                    desc="Curated, honest images. Clean sets for people and products, with print options."
                    href="/photography"
                />
            </div>
        </section>

        <!-- FEATURED WORK (placeholders) -->
        <section class="py-12 sm:py-16">
            <SectionHeading eyebrow="Featured" title="Recent work" sub="Highlights from software, music, and photography." />
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-600">Case studies coming soon.</div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-600">Music & photo highlights coming soon.</div>
            </div>
        </section>

        <!-- LATEST BLOG -->
        <section class="py-12 sm:py-16">
            <SectionHeading eyebrow="From the Blog" title="Latest writing" sub="Thoughts on building, learning, and shipping." />
            <div v-if="latestPosts.length" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <article v-for="post in latestPosts" :key="post.slug" class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-900"><a :href="`/blog/${post.slug}`" class="hover:underline">{{ post.title }}</a></h3>
                    <p class="mt-1 text-xs text-slate-500">{{ post.date }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ post.excerpt }}</p>
                    <a :href="`/blog/${post.slug}`" class="mt-3 inline-flex items-center text-sm font-medium hover:underline">Read more</a>
                </article>
            </div>
            <div v-else class="mt-2 text-slate-600">Posts will appear here automatically once wired up.</div>
        </section>

    </AppLayout>
</template>
