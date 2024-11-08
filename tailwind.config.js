const plugin = require('tailwindcss/plugin');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.{html,js,php}',
    '!./node_modules/**' // Ignore files in node_modules
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins'],
        mono: 'JetBrains Mono'
      },
      colors: {
        primary: {
          50: '#f7f9fb',
          100: '#eef3f8',
          200: '#d3e1f0',
          300: '#b7cfee',
          400: '#7faaed',
          500: '#4768ec',
          600: '#3f5ed5',
          700: '#344fa9',
          800: '#293f7d',
          900: '#233264'
        }
      }
    }
  },
  plugins: [
    // Add custom base styles
    plugin(function ({ addBase, theme }) {
      addBase({
        h1: { fontSize: theme('fontSize.5xl'), fontWeight: theme('fontWeight.extrabold') },
        h2: { fontSize: theme('fontSize.4xl'), fontWeight: theme('fontWeight.bold') },
        h3: { fontSize: theme('fontSize.3xl'), fontWeight: theme('fontWeight.bold') },
        h4: { fontSize: theme('fontSize.2xl'), fontWeight: theme('fontWeight.bold') },
        h5: { fontSize: theme('fontSize.xl'), fontWeight: theme('fontWeight.bold') },
        h6: { fontSize: theme('fontSize.lg'), fontWeight: theme('fontWeight.bold') }
      });
    })
  ]
};
