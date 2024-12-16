/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./vues/**/*.{html,twig}",
    "./js/**/*.{js,jsx,ts,tsx,vue}",
  ],
  theme: {
    extend: {
      colors: {
        transparent: 'transparent',
        black: '#000',
        white: '#fff',
        beige:'#A6989A',
        grey :'#1a202c',
        bleugris:'#577C92',
        bleugrisclair:'#8FBBD3',
        vertgris:'#415C6B',
        pink:'#E1425C',
        bluenight : '#040B40', 
        red:{
          500:'#4A0013'
        }
      },
    },
  },
  plugins: [],
}
