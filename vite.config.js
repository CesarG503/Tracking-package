import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/glass-effects.css', 'resources/js/glass-effects.js', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
