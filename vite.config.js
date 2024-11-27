import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return defineConfig({
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        server: {
            https: process.env.VITE_USE_HTTPS
                ? {
                    key: fs.readFileSync(path.resolve(__dirname, process.env.VITE_SSL_KEY_PATH)),
                    cert: fs.readFileSync(path.resolve(__dirname, process.env.VITE_SSL_CERT_PATH)),
                }
                : false,
            host: process.env.VITE_DEV_SERVER_HOST ?? 'localhost',
            port: process.env.VITE_DEV_SERVER_PORT ?? 5173,
            hmr: {
                host: process.env.VITE_DEV_SERVER_HOST ?? 'localhost',
                port: process.env.VITE_DEV_SERVER_PORT ?? 5173,
            },
        },
    });
};
