<script setup>
import { nextTick, ref } from 'vue'

const props = defineProps({
    src: { type: String, required: true },
    title: { type: String, default: 'audio' },
})

const audio = ref(null)
const isLoaded = ref(false)

async function loadAndPlay() {
    isLoaded.value = true
    await nextTick()

    try {
        await audio.value?.play()
    } catch {
        // Native controls remain available if autoplay is blocked by the browser.
    }
}
</script>

<template>
    <audio
        v-if="isLoaded"
        ref="audio"
        :src="props.src"
        controls
        preload="none"
        class="w-full"
    >
        Your browser does not support audio playback.
    </audio>
    <button
        v-else
        type="button"
        class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-900 transition hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600"
        :aria-label="`Load and play ${props.title}`"
        @click="loadAndPlay"
    >
        <span aria-hidden="true">▶</span>
        Play audio
    </button>
</template>
