<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import headshotImage from '../../../public/media/about/headshot.jpg';

// You can inline the copy (from 8A) here or import from a small JSON/MD source later.
</script>

<template>
    <AppLayout>
        <SeoHead title="About — Michael Stoffer" description="Senior full‑stack engineer, children’s songwriter, and photographer. Practical builds and thoughtful creative work." />

        <section class="pt-12 sm:pt-16 pb-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr,320px] gap-8 items-start">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">About</h1>
                    <div class="prose prose-slate max-w-none mt-6">
                        <p>I build practical software with a craftsman’s eye and a parent’s patience. On the engineering side, I ship Laravel/Vue products, integrations, and internal tools that stay easy to maintain. On the creative side, I write educational children’s songs and keep a camera close for portraits, brand sets, and the occasional quiet landscape.</p>
                        <p>I like traditions that work and tools that disappear. The job is to deliver something dependable—clear scope, steady cadence, and a handoff the next person will appreciate.</p>
                    </div>

                    <!-- Values -->
                    <section class="mt-10">
                        <h2 class="text-xl font-semibold text-slate-900">Values</h2>
                        <ul class="mt-4 space-y-3 text-slate-700">
                            <li><span class="font-medium text-slate-900">Clarity over cleverness.</span> Write it down. Keep the interfaces obvious. Don’t surprise the next maintainer.</li>
                            <li><span class="font-medium text-slate-900">Small steps, finished well.</span> Ship in slices, test what matters, and make it boring to operate.</li>
                            <li><span class="font-medium text-slate-900">Respect the audience.</span> For software, that’s the user and the future dev. For music and photos, it’s families, teachers, and the folks in front of the lens.</li>
                        </ul>
                    </section>

                    <!-- Timeline -->
                    <section class="mt-10">
                        <h2 class="text-xl font-semibold text-slate-900">Timeline</h2>
                        <ol class="mt-4 space-y-3 text-sm text-slate-700">
                            <li><span class="font-medium text-slate-900">Today —</span> Senior full‑stack engineer; children’s songwriter; photographer.</li>
                            <li><span class="font-medium text-slate-900">2018–Present —</span> Laravel + Vue feature work, integrations (auth, HubSpot, webhooks), PDFs/dashboards, and internal tools.</li>
                            <li><span class="font-medium text-slate-900">2013–2016 —</span> Built and launched client sites; learned the value of simple, durable stacks.</li>
                            <li><span class="font-medium text-slate-900">Earlier —</span> Grew up making things—music, code, and photos—one careful iteration at a time.</li>
                        </ol>
                    </section>

                    <!-- Capabilities -->
                    <section class="mt-10">
                        <h2 class="text-xl font-semibold text-slate-900">Capabilities</h2>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-700">
                            <div>
                                <h3 class="font-medium text-slate-900">Software</h3>
                                <p class="mt-1">Laravel/Vue builds, integrations, reports/PDFs, admin tools, CI/CD hygiene, observability.</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900">Children’s Music</h3>
                                <p class="mt-1">Classroom‑friendly songs, lyric sheets, visual kits, and simple videos.</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900">Photography</h3>
                                <p class="mt-1">Portraits, brand/product sets, and quiet landscapes. Clean light, honest color.</p>
                            </div>
                        </div>
                    </section>

                    <!-- CTA -->
                    <section class="mt-10">
                        <a href="/contact" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium shadow-soft hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand.accent">Get in touch</a>
                    </section>
                </div>

                <!-- Headshot -->
                <aside class="order-first lg:order-none">
                    <figure class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                        <img :src="headshotImage" alt="Michael Stoffer headshot" class="w-full aspect-square object-cover" loading="lazy" />
                        <figcaption class="p-4 text-sm text-slate-600">I prefer simple tools and clear handoffs.</figcaption>
                    </figure>
                </aside>
            </div>
        </section>
    </AppLayout>
</template>
