import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                'tam-dorado': '#ddc9a3',
                'tam-dorado-fuerte': '#bfa87e',
                'tam-rojo': '#ab0033',
                'tam-rojo-fuerte': '#8b0028',
            }
        },
    },
    plugins: [],
};
