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
        // Keep browser source maps private unless an error tracker upload step is added.
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Vue + Inertia runtime: cached separately, shared across all pages.
                    if (id.includes('/node_modules/vue/') || id.includes('/node_modules/@inertiajs/')) {
                        return 'vendor';
                    }
                },
            },
        },
    },
});
