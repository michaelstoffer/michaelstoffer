<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ posts: { type: Array, default: () => [] } })
</script>

<template>
    <AppLayout>
        <SeoHead title="Blog — Michael Stoffer" description="Practical notes on building, learning, and shipping." />

        <section class="pt-12 sm:pt-16 pb-6">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Blog</h1>
                <p class="mt-4 text-lg text-slate-700">Short, useful posts about engineering and creative work.</p>
            </div>
        </section>

        <section class="pb-12 sm:pb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <article v-for="p in props.posts" :key="p.slug" class="rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
                    <a :href="`/blog/${p.slug}`" class="block">
                        <img v-if="p.cover" :src="p.cover" :alt="`${p.title} cover`" class="w-full aspect-[4/3] object-cover" loading="lazy" />
                        <div class="p-5">
                            <h2 class="text-lg font-semibold text-slate-900">{{ p.title }}</h2>
                            <p class="mt-1 text-xs text-slate-500">{{ new Date(p.published_at).toLocaleDateString() }}</p>
                            <p class="mt-2 text-slate-600 line-clamp-3">{{ p.excerpt }}</p>
                            <span class="mt-3 inline-flex items-center text-sm font-medium text-slate-900 hover:underline">Read more</span>
                        </div>
                    </a>
                </article>
            </div>
            <p v-if="!props.posts.length" class="mt-4 text-slate-600">No posts yet.</p>
        </section>
    </AppLayout>
</template>
