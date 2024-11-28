import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/usuarios/index.js',
                'resources/js/auth/permissions/permisos.js'
            ],
            refresh: true,
        }),
    ],
});
