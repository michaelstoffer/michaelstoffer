import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vue + Inertia runtime: cached separately, shared across all pages
                    vendor: ['vue', '@inertiajs/vue3'],
                    // Markdown renderer only needed on blog/music pages
                    markdown: ['marked'],
                },
            },
        },
    },
});
