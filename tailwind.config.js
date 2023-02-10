const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: '#FC76A1',
                success: '#3aa166',
                "base-content": "#FFFFFF",
                "base-100": "#272732",
                "base-200": "#21212b",
                "base-300": "#181820"
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('flowbite/plugin'),
    ],
};
