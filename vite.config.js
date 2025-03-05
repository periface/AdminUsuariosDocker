import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/jquery/jquery.min.js',
                'resources/bootstrap/js/bootstrap.bundle.min.js',
                'resources/js/sb-admin-2.js',
                'resources/jquery-easing/jquery.easing.min.js',
                'resources/js/app.js',
                'resources/js/dimensiones/index.js',
                'resources/js/dataviz/index.js',
                'resources/css/app.css', 'resources/css/sb-admin-2.min.css',
                'resources/fontawesome-free/css/all.min.css',
            ],
            refresh: true,
        }),
    ],
});
