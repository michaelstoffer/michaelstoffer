<script setup>
import { onMounted, onBeforeUnmount, watch, ref } from 'vue'

const props = defineProps({
    open: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    index: { type: Number, default: 0 },
})
const emit = defineEmits(['update:open', 'update:index'])

const localOpen = ref(props.open)
const i = ref(props.index)

watch(() => props.open, v => localOpen.value = v)
watch(() => props.index, v => i.value = v)
watch(localOpen, v => emit('update:open', v))
watch(i, v => emit('update:index', v))

function close(){ localOpen.value = false }
function next(){ if (!props.items.length) return; i.value = (i.value + 1) % props.items.length }
function prev(){ if (!props.items.length) return; i.value = (i.value - 1 + props.items.length) % props.items.length }

function onKey(e){
    if(!localOpen.value) return
    if(e.key === 'Escape') close()
    if(e.key === 'ArrowRight') next()
    if(e.key === 'ArrowLeft') prev()
}

onMounted(() => document.addEventListener('keydown', onKey))
onBeforeUnmount(() => document.removeEventListener('keydown', onKey))
</script>

<template>
    <div v-show="localOpen" class="fixed inset-0 z-50">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80" @click="close" />

        <!-- Frame -->
        <div class="relative h-full w-full flex items-center justify-center p-4">
            <figure class="max-w-6xl w-full">
                <img :src="items[i]?.src" :alt="items[i]?.alt || 'Photo'" class="max-h-[80vh] w-auto mx-auto rounded-lg shadow-lg" />
                <figcaption class="mt-3 text-center text-sm text-slate-200" v-if="items[i]?.alt || items[i]?.location">
                    <span v-if="items[i]?.alt">{{ items[i].alt }}</span>
                    <span v-if="items[i]?.location"> • {{ items[i].location }}</span>
                </figcaption>
            </figure>

            <!-- Controls -->
            <button @click="prev" aria-label="Previous" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><path d="M15 19l-7-7 7-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button @click="next" aria-label="Next" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 shadow hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button @click="close" aria-label="Close" class="absolute top-3 right-3 rounded-full bg-white/90 p-2 shadow hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><path d="M18 6L6 18M6 6l12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>
</template>
