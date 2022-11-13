/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    colors: {
      red: 'var(--red)',
      blue: 'var(--blue)',
      green: 'var(--green)',
      beige: 'var(--beige)',
      black: 'var(--black)',
      white: 'var(--white)',
    },
    fontFamily: {
      commune: ['Commune', 'sans-serif'],
      helvetica: ['Helvetica Neue', 'sans-serif'],
    },
    fontSize: {
      300: 'var(--text-300)',
      100: 'var(--text-100)',
      70: 'var(--text-70)',
      50: 'var(--text-50)',
      30: 'var(--text-30)',
      25: 'var(--text-25)',
      20: 'var(--text-20)',
    },
    lineHeight: {
      140: '140%',
      120: '120%',
      85: '85%',
      70: '70%',  
    },
    width: {
      '1/12': '8.333333%',
      '2/12': '16.666667%',
      '3/12': '25%',
      '4/12': '33.333333%',
      '5/12': '41.666667%',
      '6/12': '50%',
      '7/12': '58.333333%',
      '8/12': '66.666667%',
      '9/12': '75%',
      '10/12': '83.333333%',
      '11/12': '91.666667%',
      full: '100%',
      screen: '100vw',
    },
    height: {
      340: '340px',
      full: '100%',
      screen: '100vw',
    },
    padding: {
      container: 'var(--size-50)',
      130: 'var(--size-130)',
      30: 'var(--size-30)',
      15: 'var(--size-15)',
    }, 
    margin: {
      10: 'var(--size-10)',
      50: 'var(--size-50)',
      130: 'var(--size-130)',
    },
    gap: {
      100: '100px',
      20: 'var(--size-20)',
      10: 'var(--size-10)', 
    },
    extend: {
      borderWidth: {
        '1px': '1px'
      }
    },
  },
  plugins: [],
} 
