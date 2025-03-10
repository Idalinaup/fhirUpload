import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/style.css', // Add your CSS files here
                'resources/css/style_1.css',
                'resources/js/change.js'
            ],
            refresh: true,
        }),
    ],
});
