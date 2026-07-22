import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                "surface-container-low": "#171b27",
                "surface": "#0e131e",
                "secondary-fixed-dim": "#bbc3ff",
                "inverse-surface": "#dee2f2",
                "surface-container-highest": "#303541",
                "on-secondary-fixed-variant": "#002cce",
                "surface-container-lowest": "#090e19",
                "on-error": "#690005",
                "primary-container": "#00f2ff",
                "primary-fixed": "#74f5ff",
                "tertiary-container": "#cddcf3",
                "secondary-fixed": "#dee0ff",
                "on-primary-fixed-variant": "#004f54",
                "on-secondary-fixed": "#000f5d",
                "surface-container": "#1b1f2b",
                "secondary": "#bbc3ff",
                "on-error-container": "#ffdad6",
                "primary": "#00f2ff",
                "on-tertiary-fixed-variant": "#39485a",
                "outline": "#849495",
                "surface-bright": "#343946",
                "on-background": "#dee2f2",
                "inverse-primary": "#00696f",
                "surface-dim": "#0e131e",
                "on-surface": "#dee2f2",
                "on-primary": "#00363a",
                "on-surface-variant": "#b9cacb",
                "outline-variant": "#3a494b",
                "surface-tint": "#00dbe7",
                "surface-container-high": "#252a36",
                "on-tertiary": "#233143",
                "secondary-container": "#052fd1",
                "on-tertiary-container": "#526174",
                "error-container": "#93000a",
                "surface-variant": "#303541",
                "on-secondary-container": "#a9b4ff",
                "on-tertiary-fixed": "#0d1c2d",
                "on-secondary": "#001d93",
                "tertiary": "#f5f7ff",
                "primary-fixed-dim": "#00dbe7",
                "on-primary-fixed": "#002022",
                "inverse-on-surface": "#2b303c",
                "on-primary-container": "#006a71",
                "error": "#ffb4ab",
                "background": "#0e131e",
                "tertiary-fixed-dim": "#b9c8de",
                "tertiary-fixed": "#d4e4fa",
                "electric-cyan": "#00f2ff",
                
                // Keep old ones for compatibility if needed
                'orq-blue': '#0e131e',
                'orq-gold': '#00f2ff',
                'orq-dark-gray': '#090e19',
                'orq-light-gray': '#1b1f2b',
            },
            borderRadius: {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
            spacing: {
                "gutter": "24px",
                "base": "4px",
                "margin-desktop": "64px",
                "container-max": "1280px",
                "margin-mobile": "16px",
                "max-width": "1280px"
            },
            fontFamily: {
                "headline-xl": ["Montserrat", ...defaultTheme.fontFamily.sans],
                "body-md": ["Hanken Grotesk", ...defaultTheme.fontFamily.sans],
                "headline-lg": ["Montserrat", ...defaultTheme.fontFamily.sans],
                "body-lg": ["Hanken Grotesk", ...defaultTheme.fontFamily.sans],
                "headline-lg-mobile": ["Montserrat", ...defaultTheme.fontFamily.sans],
                "label-md": ["JetBrains Mono", ...defaultTheme.fontFamily.mono],
                "label-sm": ["JetBrains Mono", ...defaultTheme.fontFamily.mono],
                "headline-md": ["Montserrat", ...defaultTheme.fontFamily.sans],
                "body-sm": ["Hanken Grotesk", ...defaultTheme.fontFamily.sans]
            },
            fontSize: {
                "headline-xl": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                "headline-lg-mobile": ["24px", {"lineHeight": "1.2", "fontWeight": "600"}],
                "label-md": ["14px", {"lineHeight": "1.0", "letterSpacing": "0.05em", "fontWeight": "500"}],
                "label-sm": ["12px", {"lineHeight": "1.0", "letterSpacing": "0.08em", "fontWeight": "500"}],
                "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
            }
        },
    },

    plugins: [forms],
};
