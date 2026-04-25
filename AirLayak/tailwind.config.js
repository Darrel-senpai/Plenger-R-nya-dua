/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                teal: {
                    200: '#5DCAA5',
                    400: '#1D9E75',
                    600: '#0F6E56',
                    800: '#085041',
                }
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'sans-serif'],
                display: ['Syne', 'sans-serif'],
            }
        },
    },
    plugins: [],
}