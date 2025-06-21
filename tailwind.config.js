import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue', // إذا كنت تستخدم Vue
  ],
  darkMode: 'class',

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        indigo: {
          600: '#4f46e5',
          700: '#4338ca',
        },
        // تخصيص الألوان الأخرى
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('tailwindcss-rtl'),
  ],

  safelist: [
    {
      pattern: /(bg|text|border)-(indigo|blue|red|green|yellow)-(400|500|600|700)/,
    },
  ],
};