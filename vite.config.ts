import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        // ❌ VERCEL ISSUE: Wayfinder requires PHP during build, but Vercel's Node.js build doesn't have PHP
        // ✅ FIX: Only enable wayfinder when NOT building for production (i.e., during local dev)
        // This prevents the "php: command not found" error on Vercel
        ...(process.env.NODE_ENV !== 'production' ? [
            wayfinder({
                formVariants: true,
            }),
        ] : []),
    ],
});
