import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
                display: ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    brown:   '#2F2105',
                    orange:  '#FF902A',
                    cream:   '#F6EBDA',
                    sand:    '#F9D9AA',
                    muted:   '#7E7D7A',
                    light:   '#FFF8F0',
                    dark:    '#1A1200',
                    'orange-light': '#FFD28F',
                    'orange-hover': '#E67E1A',
                },
            },
            borderRadius: {
                'xl':  '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            boxShadow: {
                'card':    '0 2px 12px rgba(47, 33, 5, 0.08)',
                'card-hover': '0 8px 32px rgba(47, 33, 5, 0.16)',
                'btn':     '0 4px 14px rgba(255, 144, 42, 0.35)',
                'nav':     '0 2px 20px rgba(47, 33, 5, 0.10)',
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '88': '22rem',
                '112': '28rem',
            },
            transitionTimingFunction: {
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },

    plugins: [forms],
};
