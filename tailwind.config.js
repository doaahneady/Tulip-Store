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
                messiri: ['El Messiri', 'sans-serif'],
            },
            fontSize: {
                'fluid-xs': 'var(--text-xs)',
                'fluid-sm': 'var(--text-sm)',
                'fluid-base': 'var(--text-base)',
                'fluid-lg': 'var(--text-lg)',
                'fluid-xl': 'var(--text-xl)',
                'fluid-2xl': 'var(--text-2xl)',
                'fluid-3xl': 'var(--text-3xl)',
                'fluid-4xl': 'var(--text-4xl)',
                'fluid-5xl': 'var(--text-5xl)',
            },
            spacing: {
                'fluid-3xs': 'var(--space-3xs)',
                'fluid-2xs': 'var(--space-2xs)',
                'fluid-xs': 'var(--space-xs)',
                'fluid-sm': 'var(--space-sm)',
                'fluid-md': 'var(--space-md)',
                'fluid-lg': 'var(--space-lg)',
                'fluid-xl': 'var(--space-xl)',
                'fluid-2xl': 'var(--space-2xl)',
                'fluid-3xl': 'var(--space-3xl)',
            },
            borderRadius: {
                'fluid-sm': 'var(--radius-sm)',
                'fluid-md': 'var(--radius-md)',
                'fluid-lg': 'var(--radius-lg)',
                'fluid-xl': 'var(--radius-xl)',
            },
            maxWidth: {
                'fluid-container': 'var(--container-max-width)',
            },
            colors: {
                tulip: {
                    orange: 'var(--color-orange)',
                    teal: 'var(--color-teal)',
                    'dark-teal': 'var(--color-dark-teal)',
                    beige: 'var(--color-beige)',
                    cream: 'var(--color-cream)',
                },
            },
        },
    },

    plugins: [forms],
};
