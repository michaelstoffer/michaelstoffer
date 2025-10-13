<template>
    <!-- MobileMenuButton.vue -->
    <button
        type="button"
        class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-transparent hover:border-gray-300"
        :aria-expanded="open ? 'true' : 'false'"
        aria-controls="mobile-nav"
        @click="$emit('toggle')"
    >
        <!-- Hamburger -->
        <svg
            v-if="!open"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="h-6 w-6"
        >
            <path d="M3 6h18M3 12h18M3 18h18" vector-effect="non-scaling-stroke" />
        </svg>

        <!-- Close (X) -->
        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="h-6 w-6"
        >
            <path d="M6 6l12 12M18 6l-12 12" vector-effect="non-scaling-stroke" />
        </svg>

        <span class="sr-only">Toggle menu</span>
    </button>
</template>

<script setup lang="ts">
defineProps<{ open: boolean }>()
defineEmits<{ (e: 'toggle'): void }>()
</script>
