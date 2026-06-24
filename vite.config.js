import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Main
                'resources/css/app.css',
                'resources/js/app.js',

                // Page CSS
                'resources/css/home.css',
                'resources/css/about.css',
                'resources/css/contact.css',

                // Page JS
                'resources/js/home.js',
                'resources/js/about.js',
                'resources/js/contact.js',
            ],
            
            refresh: true,
        }),
    ],


});