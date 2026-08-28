import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                // Bulgarian storefront: the `cyrillic` subset is NOT optional.
                // `subsets` defaults to ['latin'] only — omitting it renders
                // every Cyrillic character in a system fallback font.
                //
                // Nunito carries both roles — body and display. Its rounded
                // terminals are the type half of the rounded-forms direction,
                // and one family at several weights keeps headlines and body
                // speaking with the same voice.
                bunny('Nunito', {
                    weights: [400, 500, 600, 700, 800, 900],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                }),
                bunny('IBM Plex Mono', {
                    weights: [400, 500],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                }),
            ],
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ],
    server: {
        watch: {
            ignored: [
                '**/.agents/**',
                '**/.claude/**',
                '**/.cursor/**',
                '**/.junie/**',
                '**/vendor/**',
            ],
        },
    },
});
