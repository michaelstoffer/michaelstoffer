<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ButtonLink from '@/Components/ButtonLink.vue'
import PillarCard from '@/Components/PillarCard.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import BlogCard from "@/Components/BlogCard.vue";
import CaseStudyCard from "@/Components/CaseStudyCard.vue";
import LookingBack from "../Components/LookingBack.vue";

const props = defineProps({
    latestPosts: { type: Array, default: () => [] },
    caseStudies: { type: Array, default: () => [] },
    songs: { type: Array, default: () => [] },
    amr: { type: String, default: () => '' }
})
</script>

<template>
    <AppLayout>
        <SeoHead title="Home — Michael Stoffer" description="Engineer, songwriter, photographer—building things that last." />

        <!-- HERO -->
        <section class="pt-16 sm:pt-20 pb-12 sm:pb-16">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-5xl py-2 font-semibold tracking-tight bg-linear-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
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

        <!-- SOFTWARE -->
        <div class="mt-12 grid grid-cols-1 gap-6">
            <section class="">
                <SectionHeading eyebrow="Proof" title="Recent case studies" sub="Problem → approach → result from real projects." linkHref="/software" linkText="All case studies" />
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <CaseStudyCard v-for="cs in props.caseStudies" :key="cs.slug"
                                   :title="cs.title" :summary="cs.summary" :href="`/software/${cs.slug}`"
                                   :cover="cs.cover" :problem="cs.problem" :approach="cs.approach" :result="cs.result" :tags="cs.tags" />
                </div>

                <div class="mt-8 sm:hidden">
                    <a href="/software" class="inline-flex items-center text-sm font-medium text-slate-900 hover:underline">View all case studies</a>
                </div>
            </section>
        </div>

        <!-- LATEST BLOG -->
        <section class="py-12 sm:py-16">
            <SectionHeading eyebrow="From the Blog" title="Latest writing" sub="Thoughts on building, learning, and shipping.", linkHref="/blog" linkText="All posts" />
            <div v-if="latestPosts.length" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <BlogCard v-for="p in latestPosts" :key="p.slug" :post="p" />
            </div>
            <div v-else class="mt-2 text-slate-600">Posts will appear here automatically once wired up.</div>

            <div class="mt-8 sm:hidden">
                <a href="/blog" class="inline-flex items-center text-sm font-medium text-slate-900 hover:underline">View all blog posts</a>
            </div>
        </section>

        <!-- MUSIC -->
        <section class="py-12 sm:py-16">
            <SectionHeading eyebrow="Music" title="From the studio" sub="Music from the heart, written by yours truly." linkHref="/music" linkText="All songs" />
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <article v-for="s in props.songs" :key="s.slug" class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                    <a :href="`/music/${s.slug}`" class="block">
                        <img v-if="s.cover" :src="s.cover" :alt="s.title + ' cover'" class="w-full aspect-square object-cover" loading="lazy" />
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-slate-900">{{ s.title }}</h3>
                            <p v-if="s.duration" class="mt-1 text-xs text-slate-500">{{ s.duration }}</p>
                            <p class="mt-2 text-slate-600 line-clamp-3">{{ s.summary }}</p>
                        </div>
                    </a>
                    <div v-if="s.audioSrc" class="px-5 pb-5"><audio :src="s.audioSrc" controls class="w-full"></audio></div>
                </article>
            </div>

            <div class="mt-8 sm:hidden">
                <a href="/music" class="inline-flex items-center text-sm font-medium text-slate-900 hover:underline">View all songs</a>
            </div>
        </section>
    </AppLayout>
</template>
