// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                fontFamily: {
                    sans: ['Raleway', ...defaultTheme.fontFamily.sans],
                },

                // Tipografía base utilizando Figtree
               /*  sans: ['Figtree', ...defaultTheme.fontFamily.sans], */
            },
            colors: {
                // Paleta personalizada
                primary: '#2F6690',         // Azul profundo principal
                'primary-light': '#3A7CA5', // Azul intermedio para hover o bordes
                background: '#D9DCD6',      // Gris claro verdoso para fondo base este lo ocupo en el body
                dark: '#16425B',            // Azul petróleo oscuro para contraste fuerte generalmete para TITULOS
                accent: '#81C3D7',          // Celeste brillante para acentos o elementos destacados
                sage: '#D9DCD6',            // Alias adicional para compatibilidad con bg-sage
            },
        },
    },
    plugins: [forms, typography],
}