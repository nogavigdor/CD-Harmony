/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{html,css,js}', 
    './*.php','./*.html'
  ],
  theme: {
    extend: {
      colors: {
        'background-color': '#008080',  
        'text-color': '#A80000',
        'accent-color': '#DAA520',
        'secondary-background': '#FFF4E0',
        'menu-background': '#006B6B',
        'menu-text': '#FFF4E0',
        'hover-states': '#009494',
        'border-color': '#333333',
      },
     
    },
  },
  plugins: [require("daisyui")],
}

