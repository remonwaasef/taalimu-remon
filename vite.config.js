import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js', 'resources/css/landing-new.css'],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // استخدام IPv4 بدلاً من IPv6
        port: 5173,
        strictPort: true,
    },
});
