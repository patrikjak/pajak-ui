import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Full bundle — all components
                'resources/assets/js/main.ts',
                'resources/assets/css/main.scss',

                // Per-feature CSS — include only what you need
                'resources/assets/css/form/index.scss',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
        manifest: false,
        emptyOutDir: true,
        outDir: 'public/assets',
    },
    server: {
        host: '0.0.0.0',
        origin: 'https://vite.ui.pajak.local',
        cors: {
            origin: 'https://design.pajak.local',
        },
        hmr: {
            host: 'vite.ui.pajak.local',
            protocol: 'wss',
        },
    },
});
