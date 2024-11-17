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
                'ap1-hover': '#D4B87E', // Slightly darker for hover
                'ap2': '#42210B',
                'ap3': '#EA9C6B',
                'ap3-hover': '#D98B5C',
                'ap4': '#5000D0',
                'ap4-hover': '#4500B8',
                'ap5': '#6F72FF',
                'ap5-hover': '#5E63E6',
                'ap6': '#9097FF',
                'ap6-hover': '#7A84E0',
                'ap7': '#6F3AFF',
                'ap7-hover': '#5C2ED1',
                'ap8': '#42210B',
              },
        },
    },

    plugins: [forms, typography],
};
