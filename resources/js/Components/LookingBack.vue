<script setup>
import SectionHeading from '@/Components/SectionHeading.vue'

const props = defineProps({ amr: { type: String, default: () => '' } })
</script>
<template>
    <!-- Looking Back -->
    <section class="pb-12 sm:pb-16">
        <div class="max-w-xl">
            <SectionHeading eyebrow="Apple Music Replay" title="Looking Back at 2025" sub="Thanks for listening and showing up this year." />

            <!-- Video -->
            <div v-if="amr" class="mt-6 aspect-[9/16] w-full rounded-2xl overflow-hidden border border-slate-200 bg-white">
                <video autoplay loop muted preload="auto" width="100%" class="max-w-full">
                    <source :src="amr" type="video/mp4" />
                    <!-- Fallback text for very old browsers -->
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </section>
</template>
