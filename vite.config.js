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
                'resources/css/campaigns.css',

                'resources/css/search.css',
                'resources/css/impact.css',
                'resources/css/faq.css',
                'resources/css/legal.css',
                'resources/css/errors.css',
                'resources/css/errors-3.css',
                'resources/css/errors-4.css',
                'resources/css/newsletter-unsubscribed.css',
                'resources/css/public-show.css',
                'resources/css/public-show-new.css',
                'resources/css/blogs.css',
                'resources/css/blog-show.css',
                'resources/css/partnership.css',
                'resources/css/ddrf.css',
                'resources/css/campaigns-index.css',
                'resources/css/campaigns-old.css',
                'resources/css/campaigns-show-new.css',
                'resources/css/campaigns-create.css',
                'resources/css/how-it-works.css',
                'resources/css/events-index.css',
                'resources/css/events-view.css',
                'resources/css/events-register.css',
                'resources/css/job-posts-index.css',
                'resources/css/job-posts-show.css',
                'resources/css/payment.css',

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