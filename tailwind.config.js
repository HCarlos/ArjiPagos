import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    50: '#f0f4ff',
                    100: '#e0e8f9',
                    600: '#4f46e5',
                    700: '#4338ca'
                }
            },
            borderRadius: {
                xl: '12px',
                '2xl': '16px'
            }
        },
    },

    plugins: [forms],
};
