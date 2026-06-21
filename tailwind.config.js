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
            colors: {
                "inverse-on-surface": "#ffede5",
                "tertiary": "#a33d23",
                "primary": "#9b4500",
                "tertiary-container": "#ff8a6d",
                "surface-variant": "#f2dfd5",
                "on-surface": "#231914",
                "on-error": "#ffffff",
                "secondary-container": "#ffab69",
                "primary-container": "#ff8c42",
                "on-primary-fixed": "#331200",
                "on-secondary-fixed-variant": "#6f3800",
                "tertiary-fixed": "#ffdad2",
                "surface-bright": "#fff8f6",
                "secondary": "#8e4e14",
                "on-background": "#231914",
                "surface-container-low": "#fff1eb",
                "surface-dim": "#ead6cd",
                "on-secondary-fixed": "#2f1400",
                "outline-variant": "#ddc1b3",
                "surface-container-high": "#f8e4db",
                "secondary-fixed": "#ffdcc4",
                "surface-container": "#feeae0",
                "on-primary-fixed-variant": "#763300",
                "on-secondary-container": "#783d01",
                "on-secondary": "#ffffff",
                "surface-tint": "#9b4500",
                "on-primary-container": "#6a2d00",
                "on-tertiary-fixed": "#3c0700",
                "inverse-surface": "#3a2e28",
                "tertiary-fixed-dim": "#ffb4a2",
                "background": "#fff8f6",
                "inverse-primary": "#ffb68d",
                "primary-fixed-dim": "#ffb68d",
                "surface": "#fff8f6",
                "outline": "#897266",
                "error-container": "#ffdad6",
                "secondary-fixed-dim": "#ffb780",
                "on-primary": "#ffffff",
                "on-error-container": "#93000a",
                "surface-container-lowest": "#ffffff",
                "on-surface-variant": "#564338",
                "primary-fixed": "#ffdbc9",
                "on-tertiary-fixed-variant": "#83260e",
                "error": "#ba1a1a",
                "on-tertiary": "#ffffff",
                "on-tertiary-container": "#781e07",
                "surface-container-highest": "#f2dfd5"
            },
            spacing: {
                "gutter": "24px",
                "card-padding": "24px",
                "margin-tablet": "32px",
                "unit": "8px",
                "container-max": "1280px",
                "margin-desktop": "64px"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                "display-lg": ["Plus Jakarta Sans", "sans-serif"],
                "label-lg": ["Plus Jakarta Sans", "sans-serif"],
                "label-md": ["Plus Jakarta Sans", "sans-serif"],
                "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                "body-md": ["Work Sans", "sans-serif"],
                "body-lg": ["Work Sans", "sans-serif"]
            },
            fontSize: {
                "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "800" }],
                "label-lg": ["14px", { "lineHeight": "20px", "fontWeight": "600" }],
                "label-md": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }]
            }
        },
    },

    plugins: [forms],
};