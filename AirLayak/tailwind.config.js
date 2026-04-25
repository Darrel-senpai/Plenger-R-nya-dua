/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            animation: {
                blink: 'blink 2s infinite',
                'blink-fast': 'blink 1.5s infinite',
                'blink-slow': 'blink 1s infinite',
            },
            keyframes: {
                blink: { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.5 } },
            },
            colors: {
                blue: { DEFAULT: '#1A6BCC', light: '#EBF3FF', dark: '#1558AA' },
                amber: { DEFAULT: '#D97706', light: '#FEF3C7' },
                danger: { DEFAULT: '#DC2626', light: '#FEE2E2', ring: '#EF4444' },
                teal: {
                    200: '#5DCAA5',
                    400: '#1D9E75',
                    600: '#0F6E56',
                    800: '#085041',
                }
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'sans-serif'],
                mono: ['Space Mono', 'monospace'],
                display: ['Syne', 'sans-serif'],
            }
        },
    },
    plugins: [],
}