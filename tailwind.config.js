import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Lexend Deca', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'ap1': '#EADABD',
                'ap2': '#42210B',
                'ap3': '#EA9C6B',
                'ap4': '#5000D0',
                'ap5': '#6F72FF',
                'ap6': '#9097FF',
                'ap7': '#6F3AFF',
              },
        },
    },

    plugins: [forms, typography],
};
