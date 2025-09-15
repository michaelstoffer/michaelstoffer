<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps({
    href: { type: String, required: true },
    as: { type: String, default: 'link' }, // 'link' | 'a'
    variant: { type: String, default: 'primary' }, // primary | outline | ghost
    size: { type: String, default: 'md' }, // sm | md | lg
})

const classes = ({variant, size}) => {
    const base = 'inline-flex items-center gap-2 rounded-2xl font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-brand.accent transition shadow-soft'
    const variants = {
        primary: 'bg-slate-900 text-white hover:opacity-95',
        outline: 'bg-white text-slate-900 border border-slate-300 hover:bg-slate-50',
        ghost: 'bg-transparent text-slate-900 hover:bg-slate-100',
    }
    const sizes = { sm: 'px-3 py-1.5 text-sm', md: 'px-4 py-2 text-sm', lg: 'px-5 py-3 text-base' }
    return `${base} ${variants[variant]} ${sizes[size]}`
}
</script>

<template>
    <component :is="as === 'a' ? 'a' : Link" :href="href" :class="classes({variant, size})">
        <slot />
    </component>
</template>
