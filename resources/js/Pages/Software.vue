<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import ButtonLink from '@/Components/ButtonLink.vue'
import CaseStudyCard from '@/Components/CaseStudyCard.vue'

const props = defineProps({
    services: { type: Array, default: () => [] },
    caseStudies: { type: Array, default: () => [] },
})
</script>

<template>
    <AppLayout>
        <SeoHead title="Software — Michael Stoffer" description="Laravel + Vue builds, integrations, PDFs, dashboards, and internal tools." />

        <!-- Intro -->
        <section class="pt-12 sm:pt-16 pb-8">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Software Engineering</h1>
                <p class="mt-4 text-lg text-slate-700">I build practical Laravel/Vue features, integrations, and internal tools with a clean, traditional approach—ship it, document it, and keep it easy to maintain.</p>
            </div>
        </section>

        <!-- Services -->
        <section class="py-8">
            <SectionHeading eyebrow="Services" title="What I do" sub="Clear scopes and dependable delivery." />
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="s in services" :key="s.title" class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-base font-semibold text-slate-900">{{ s.title }}</h3>
                    <p class="mt-2 text-slate-600">{{ s.desc }}</p>
                </div>
            </div>
        </section>

        <!-- Case Studies -->
        <section class="py-12 sm:py-16">
            <SectionHeading eyebrow="Proof" title="Recent case studies" sub="Problem → approach → result." />
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <CaseStudyCard v-for="cs in caseStudies" :key="cs.slug" :title="cs.title" :summary="cs.summary" :href="`/software/${cs.slug}`" :problem="cs.problem" :approach="cs.approach" :result="cs.result" :tags="cs.tags" />
            </div>
            <div class="mt-10">
                <ButtonLink href="/contact">Start a project</ButtonLink>
            </div>
        </section>

        <!-- FAQ (optional) -->
        <section class="py-12">
            <SectionHeading eyebrow="Process" title="How I work" />
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-slate-700">
                <div>
                    <h3 class="font-medium text-slate-900">Simple scopes</h3>
                    <p class="mt-2">Small milestones, written acceptance criteria, and a done‑means‑done checklist.</p>
                </div>
                <div>
                    <h3 class="font-medium text-slate-900">Tested & observable</h3>
                    <p class="mt-2">Coverage where it counts, queue visibility, and a clean error inbox.</p>
                </div>
                <div>
                    <h3 class="font-medium text-slate-900">Handoffs that stick</h3>
                    <p class="mt-2">Docs, admin notes, and a tidy backlog for the next dev.</p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
