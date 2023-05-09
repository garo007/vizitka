import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 1000000,
    },
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/scss/admin/app.scss',
                'resources/js/admin/app.js',
                'resources/scss/client/app.scss',
                'resources/js/client/app.js'
            ],
            refresh: true,
        }),
    ],
});
