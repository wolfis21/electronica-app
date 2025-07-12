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
                // 'sans' será la fuente para el cuerpo del texto
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                // 'serif' será la fuente para los títulos
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Aquí definimos tu paleta de colores
                'orq-blue': '#1A237E',         // Azul Noche [cite: 45]
                'orq-gold': '#FBC02D',        // Dorado/Mostaza [cite: 46]
                'orq-dark-gray': '#263238', // Texto Principal [cite: 48]
                'orq-light-gray': '#ECEFF1',  // Fondos Secundarios [cite: 47]
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
