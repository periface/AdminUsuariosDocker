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
                // 'resources/js/usuarios/index.js',
                // 'resources/js/auth/permissions/permisos.js',
                'resources/js/evaluaciones/evaluaciones.js',
                // 'resources/js/areas/index.js',
                'resources/js/dimensiones/index.js',
                'resources/js/dataviz/index.js',
            ],
            refresh: true,
        }),
    ],
});
