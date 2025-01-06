import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import scrollbar from 'tailwind-scrollbar'
import plugin from 'tailwindcss/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        data: {
            invalid: 'invalid=true',
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                math: ['Noto Sans Math', 'math']
            },
        },
    },

    plugins: [
        forms,
        scrollbar,
        plugin(({ addUtilities }) => {
            addUtilities({
                ".overflow-anchor-none": {
                    overflowAnchor: "none",
                },
            });
        })
    ],
};
