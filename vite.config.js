import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1',
    },
    plugins: [
        laravel({
            input: [
                // Main
                'resources/css/app.css',
                'resources/js/app.js',

                // Page CSS
                'resources/css/home.css',
                'resources/css/footer.css',
                'resources/css/about.css',
                'resources/css/contact.css',

                // Page JS
                'resources/js/home.js',
                'resources/js/about.js',
                'resources/js/contact.js',
                'resources/js/campaigns.js',

                // Admin
                'resources/css/admin.css',
                'resources/js/admin.js',

                // User Portal
                'resources/css/user.css',
                'resources/js/user.js',

                // Chatbot
                'resources/css/chatbot.css',
                'resources/js/chatbot.js',

                // Navbar
                'resources/css/navbar.css',
                'resources/js/navbar.js',
            ],
            
            refresh: true,
        }),
    ],


});