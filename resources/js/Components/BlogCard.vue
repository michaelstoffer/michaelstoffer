<script setup>
const props = defineProps({ post: { type: Object, required: true } })
</script>
<template>
    <article class="rounded-2xl border border-slate-200 bg-white overflow-hidden hover:shadow-lg transition-shadow">
        <a :href="`/blog/${post.slug}`" class="block">
            <img v-if="post.cover" :src="post.cover" :alt="`${post.title} cover`" class="w-full aspect-[4/3] object-cover" loading="lazy" />
            <div class="p-5">
                <h2 class="text-lg font-semibold text-slate-900">{{ post.title }}</h2>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span v-for="t in post.tags" :key="t" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-2 py-0.5 text-xs text-slate-700">{{ t }}</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">{{ new Date(post.published_at).toLocaleDateString() }}</p>
                <p class="mt-2 text-slate-600 line-clamp-3">{{ post.excerpt }}</p>
                <span class="mt-3 inline-flex items-center text-sm font-medium text-slate-900 hover:underline">Read more</span>
            </div>
        </a>
    </article>
</template>
