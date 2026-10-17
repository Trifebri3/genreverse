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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Playful Vibrant Blue Palette (transformed from previous green)
                forest: {
                    DEFAULT: '#2563EB',
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#172554',
                },
                // Playful Cyan & Sky Blue (secondary accent)
                teal: {
                    DEFAULT: '#0284C7',
                    50: '#F0F9FF',
                    100: '#E0F2FE',
                    200: '#BAE6FD',
                    300: '#7DD3FC',
                    400: '#38BDF8',
                    500: '#0EA5E9',
                    600: '#0284C7',
                    700: '#0369A1',
                    800: '#075985',
                    900: '#0C4A6E',
                },
                // Cute Sunshine Yellow (warm cheerful playful accent)
                sunshine: {
                    DEFAULT: '#FBBF24',
                    50: '#FFFBEB',
                    100: '#FEF3C7',
                    200: '#FDE68A',
                    300: '#FCD34D',
                    400: '#FBBF24',
                    500: '#F59E0B',
                    600: '#D97706',
                    700: '#B45309',
                    800: '#92400E',
                    900: '#78350F',
                },
                // Fresh Pastel Ice-Blue Background
                ivory: {
                    DEFAULT: '#F0F7FF',
                    50: '#FFFFFF',
                    100: '#F8FAFC',
                    200: '#F0F7FF',
                    300: '#E2E8F0',
                    400: '#CBD5E1',
                    500: '#94A3B8',
                    600: '#64748B',
                    700: '#475569',
                    800: '#334155',
                    900: '#1E293B',
                },
                // Cute Playful Bubblegum & Lavender Accents
                bubblegum: {
                    DEFAULT: '#F43F5E',
                    light: '#FFE4E6',
                    50: '#FFF1F2',
                },
                purple: {
                    DEFAULT: '#8B5CF6',
                    light: '#EDE9FE',
                    50: '#FAF5FF',
                },
            },
        },
    },

    plugins: [forms],
};
