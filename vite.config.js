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
                'resources/assets/js/accordion/accordion.ts',

                // Card
                'resources/assets/css/card/card-standalone.scss',

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

                // Empty state
                'resources/assets/css/empty-state/empty-state-standalone.scss',

                // Error page
                'resources/assets/css/error-page/error-page-standalone.scss',

                // Progress
                'resources/assets/css/progress/progress-standalone.scss',

                // Skeleton
                'resources/assets/css/skeleton/skeleton-standalone.scss',

                // Stepper
                'resources/assets/css/stepper/stepper-standalone.scss',

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

                // Dialog
                'resources/assets/js/dialog/dialog.ts',
                'resources/assets/css/dialog/dialog-standalone.scss',

                // Modal
                'resources/assets/js/modal/modal.ts',
                'resources/assets/css/modal/modal-standalone.scss',

                // Segmented
                'resources/assets/js/segmented/segmented.ts',
                'resources/assets/css/segmented/segmented-standalone.scss',

                // Drawer
                'resources/assets/js/drawer/drawer.ts',
                'resources/assets/css/drawer/drawer-standalone.scss',

                // Popover
                'resources/assets/js/popover/popover.ts',
                'resources/assets/css/popover/popover-standalone.scss',

                // Navbar
                'resources/assets/css/navbar/navbar-standalone.scss',

                // Sidebar
                'resources/assets/css/sidebar/sidebar-standalone.scss',

                // Table
                'resources/assets/css/table/table-standalone.scss',
                'resources/assets/js/table/table.ts',

                // Email templates
                'resources/assets/css/email/email-standalone.scss',
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
