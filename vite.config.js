import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/css/tailwind.css',
                'resources/css/dark-mode-global.css',
                'resources/js/app.js',
                'resources/js/app.jsx',
                'resources/js/taalimu-global.js',
                'resources/css/landing-new.css'
            ],
            refresh: true,
        }),
        react(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                }
            }
        }
    },
    server: {
        host: '127.0.0.1', // استخدام IPv4 بدلاً من IPv6
        port: 5173,
        strictPort: true,
    },
});
