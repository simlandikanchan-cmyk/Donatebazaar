import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/public/app.css',
                'resources/css/admin/admin.css',
                'resources/css/user/user.css',
                'resources/js/public/app.js',
                'resources/js/admin/admin.js',
                'resources/js/user/user.js',
            ],
            refresh: true,
        }),
    ],
});
