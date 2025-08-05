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
                sans: ['Poppins', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }], // 12px
                'sm': ['0.875rem', { lineHeight: '1.25rem' }], // 14px
                'base': ['1rem', { lineHeight: '1.5rem' }], // 16px
                'lg': ['1.125rem', { lineHeight: '1.75rem' }], // 18px
                'xl': ['1.25rem', { lineHeight: '1.75rem' }], // 20px
                '2xl': ['1.5rem', { lineHeight: '2rem' }], // 24px
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }], // 30px
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }], // 36px
            },
            colors: {
                primary: {
                    DEFAULT: '#3b82f6', // blue-500
                    light: '#60a5fa', // blue-400
                    dark: '#2563eb', // blue-600
                },
                status: {
                    active: {
                        bg: '#dcfce7',
                        text: '#15803d',
                    },
                    pending: {
                        bg: '#ffedd5',
                        text: '#c2410c',
                    },
                    completed: {
                        bg: '#dcfce7',
                        text: '#15803d',
                    },
                    inactive: {
                        bg: '#fef2f2',
                        text: '#dc2626',
                    },
                },
                action: {
                    view: '#3b82f6', // blue-500
                    edit: '#f59e0b', // amber-500
                    delete: '#ef4444', // red-500
                },
                table: {
                    header: '#3b82f6', // blue-500
                },
            },
            borderRadius: {
                'lg': '0.5rem', // 8px
            },
            boxShadow: {
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
        },
    },

    plugins: [forms],
};
