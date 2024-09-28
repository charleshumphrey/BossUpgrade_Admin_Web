/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        fontFamily: {
            poppins_regular: ["Poppins-Regular", "sans-serif"],
            poppins_thin: ["Poppins-Thin", "sans-serif"],
            poppins_bold: ["Poppins-Bold", "sans-serif"],
            poppins_italic: ["Poppins-Italic", "sans-serif"],
            bungee: ["Bungee", "sans-serif"],
            body: ["poppins_regular"],
        },
        extend: {
            colors: {
                "primary-color": "#000",
                "black-erie": "#191919",
                "tertiary-color": "#25242a",
                "secondary-color": "#FBFBFB",
                "accent-color": "#4892fa",
                "background-gray": "#555555",
            },
            zIndex: {
                "-1": "-1",
            },
            flexGrow: {
                5: "5",
            },
        },
    },
    plugins: [],
};
