import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        container: {
            center: true,
            padding: "1rem",
            screens: {
                sm: "640px",
                md: "768px",
                lg: "1024px",
                xl: "1280px",
                "2xl": "1400px", // デフォルトは1536pxですが、小さく設定
            },
        },
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                mincho: ['"Yu Mincho"', "YuMincho", "serif"],
            },
            colors: {
                main: {
                    50: "#fafafa",
                    100: "#f2f2f3",
                    200: "#e5e5e6",
                    300: "#d0d1d2",
                    400: "#B2B3B5",
                    500: "#737578",
                    600: "#525456",
                    700: "#3c3d3e",
                    800: "#232324",
                    900: "#111212",
                    950: "#0a0a0a",
                },
            },
        },
    },

    plugins: [forms, require("@tailwindcss/aspect-ratio")],
};
