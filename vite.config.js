import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Full bundle — all components
                'resources/assets/js/main.ts',
                'resources/assets/css/main.scss',

                // Per-feature bundles — include only what you need

                // Accordion
                'resources/assets/css/accordion/accordion-standalone.scss',

                // Card
                'resources/assets/css/card/card-standalone.scss',
                'resources/assets/js/accordion/accordion.ts',

                // Alert
                'resources/assets/css/alert/alert-standalone.scss',

                // Banner
                'resources/assets/css/banner/banner-standalone.scss',
                'resources/assets/js/banner/banner.ts',

                // Avatar
                'resources/assets/css/avatar/avatar-standalone.scss',

                // Badge
                'resources/assets/css/badge/badge-standalone.scss',

                // Breadcrumb
                'resources/assets/css/breadcrumb/breadcrumb-standalone.scss',

                // Divider
                'resources/assets/css/divider/divider-standalone.scss',

                // Progress
                'resources/assets/css/progress/progress-standalone.scss',

                // Skeleton
                'resources/assets/css/skeleton/skeleton-standalone.scss',

                // Spinner
                'resources/assets/css/spinner/spinner-standalone.scss',

                // Tooltip
                'resources/assets/css/tooltip/tooltip-standalone.scss',

                // Form components
                'resources/assets/css/form/form-standalone.scss',
                'resources/assets/js/form/form.ts',
                'resources/assets/js/form/repeater.ts',
                'resources/assets/js/form/slider.ts',

                // Toast notifications
                'resources/assets/css/toast/toast-standalone.scss',
                'resources/assets/js/toast/toast.ts',

                // HTTP connector
                'resources/assets/js/http/connector.ts',

                // Tabs
                'resources/assets/css/tabs/tabs-standalone.scss',
                'resources/assets/js/tabs/tabs.ts',

                // Calendar
                'resources/assets/js/calendar/calendar.ts',
                'resources/assets/css/calendar/calendar-standalone.scss',
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
