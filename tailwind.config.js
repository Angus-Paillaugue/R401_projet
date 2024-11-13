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
          50: '#F6F2F7',
          100: '#E9E1ED',
          200: '#CABAD4',
          300: '#A993B8',
          400: '#6E5885',
          500: '#38284f',
          600: '#302147',
          700: '#24163B',
          800: '#1A0F30',
          900: '#100824',
          950: '#090317'
        }
      }
    }
  },
  plugins: [
    // Add custom base styles
    plugin(function ({ addBase, theme }) {
      addBase({
        h1: {
          fontSize: theme('fontSize.5xl'),
          fontWeight: theme('fontWeight.extrabold')
        },
        h2: {
          fontSize: theme('fontSize.4xl'),
          fontWeight: theme('fontWeight.bold')
        },
        h3: {
          fontSize: theme('fontSize.3xl'),
          fontWeight: theme('fontWeight.bold')
        },
        h4: {
          fontSize: theme('fontSize.2xl'),
          fontWeight: theme('fontWeight.bold')
        },
        h5: {
          fontSize: theme('fontSize.xl'),
          fontWeight: theme('fontWeight.bold')
        },
        h6: {
          fontSize: theme('fontSize.lg'),
          fontWeight: theme('fontWeight.bold')
        }
      });
    })
  ]
};
