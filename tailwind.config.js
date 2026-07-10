import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Playfair Display', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#FBF8F3',
                    100: '#F6ECD9',
                    200: '#ECCFA2',
                    300: '#E1B06C',
                    400: '#D49242',
                    500: '#B88A44', // Primary Color
                    600: '#9E7134',
                    700: '#7E5727',
                    800: '#5F401D',
                    900: '#422C14',
                }
            }
        },
    },

    plugins: [forms],
};

