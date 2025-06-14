import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import inject from '@rollup/plugin-inject';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js','resources/js/js/arjiapp.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                reactivityTransform: true,
            },
        }),
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),

    ],
    server: {
        // host: '192.168.56.77',
        host: '192.168.255.2',
        mimetype: 'text/html',
        watch: {
            usePolling: true,
        },
    },
    optimizeDeps: {
        include: ['jquery', 'select2', 'datatables.net-vue3', 'datatables.net-dt'],
    },
    build: {
        chunkSizeWarningLimit: 10000,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor'
                    }
                }
            }
        }
    }
});

// host: '192.168.56.77',
// host: '192.168.255.2',
