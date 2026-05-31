<script setup>
import { onMounted, onBeforeUnmount, watch, ref, nextTick } from 'vue'

const props = defineProps({
    open: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    index: { type: Number, default: 0 },
})
const emit = defineEmits(['update:open', 'update:index'])

const localOpen = ref(props.open)
const i = ref(props.index)
const dialogEl = ref(null)
const closeBtn = ref(null)
// Tracks the element that opened the lightbox so we can restore focus on close
let openerEl = null

watch(() => props.open, v => localOpen.value = v)
watch(() => props.index, v => i.value = v)
watch(localOpen, async v => {
    emit('update:open', v)
    if (v) {
        openerEl = document.activeElement
        await nextTick()
        closeBtn.value?.focus()
    } else {
        openerEl?.focus()
    }
})
watch(i, v => emit('update:index', v))

function close(){ localOpen.value = false }
function next(){ if (!props.items.length) return; i.value = (i.value + 1) % props.items.length }
function prev(){ if (!props.items.length) return; i.value = (i.value - 1 + props.items.length) % props.items.length }

const FOCUSABLE = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'

function onKey(e){
    if (!localOpen.value) return
    if (e.key === 'Escape') { close(); return }
    if (e.key === 'ArrowRight') { next(); return }
    if (e.key === 'ArrowLeft') { prev(); return }
    // Focus trap
    if (e.key === 'Tab' && dialogEl.value) {
        const focusable = [...dialogEl.value.querySelectorAll(FOCUSABLE)]
        if (!focusable.length) { e.preventDefault(); return }
        const first = focusable[0]
        const last = focusable[focusable.length - 1]
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault(); last.focus()
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault(); first.focus()
        }
    }
}

onMounted(() => document.addEventListener('keydown', onKey))
onBeforeUnmount(() => document.removeEventListener('keydown', onKey))
</script>

<template>
    <div
        v-show="localOpen"
        ref="dialogEl"
        role="dialog"
        aria-modal="true"
        :aria-label="items[i]?.alt ? `Photo: ${items[i].alt}` : `Photo ${i + 1} of ${items.length}`"
        class="fixed inset-0 z-50"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80" @click="close" aria-hidden="true" />

        <!-- Frame -->
        <div class="relative h-full w-full flex items-center justify-center p-4">
            <figure class="max-w-6xl w-full">
                <img :src="items[i]?.src" :alt="items[i]?.alt || 'Photo'" class="max-h-[80vh] w-auto mx-auto rounded-lg shadow-lg" />
                <figcaption class="mt-3 text-center text-sm text-slate-200" v-if="items[i]?.alt || items[i]?.location">
                    <span v-if="items[i]?.alt">{{ items[i].alt }}</span>
                    <span v-if="items[i]?.location"> • {{ items[i].location }}</span>
                </figcaption>
            </figure>

            <!-- aria-live so screen readers announce the current photo on navigation -->
            <div aria-live="polite" aria-atomic="true" class="sr-only">
                {{ items[i]?.alt || `Photo ${i + 1}` }} — {{ i + 1 }} of {{ items.length }}
            </div>

            <!-- Controls -->
            <button @click="prev" aria-label="Previous photo" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow hover:bg-white focus-visible:ring-2 focus-visible:ring-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true"><path d="M15 19l-7-7 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button @click="next" aria-label="Next photo" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow hover:bg-white focus-visible:ring-2 focus-visible:ring-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button ref="closeBtn" @click="close" aria-label="Close lightbox" class="absolute top-3 right-3 rounded-full bg-white/90 p-2 shadow hover:bg-white focus-visible:ring-2 focus-visible:ring-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>
</template>
