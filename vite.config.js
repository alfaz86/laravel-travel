import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: process.env.VITE_DEV_SERVER_HOST ?? 'localhost',
        port: 5173,
        hmr: {
            host: process.env.VITE_DEV_SERVER_HOST ?? 'localhost',
            port: 5173,
        },
    },

});
