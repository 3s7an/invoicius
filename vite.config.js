import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { sentryVitePlugin } from '@sentry/vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // Uploads source maps to Sentry so stack traces resolve to real source.
        // Only runs when SENTRY_AUTH_TOKEN is present (CI/build step) — never on the
        // production VPS, where the token must not be set.
        process.env.SENTRY_AUTH_TOKEN &&
            sentryVitePlugin({
                org: process.env.SENTRY_ORG,
                project: process.env.SENTRY_PROJECT || 'invoicius-frontend',
                authToken: process.env.SENTRY_AUTH_TOKEN,
            }),
    ],
    build: {
        sourcemap: true,
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: process.env.CHOKIDAR_USEPOLLING === 'true',
        },
    },
});
