module.exports = {
  content: [
    './src/**/*.{html,css,js}', 
    './*.php',
    './views/*.php'
  ],
  theme: {
    extend: {
      fontFamily: {
        'body': ['Poppins', 'sans'], // Use 'Poppins' for the default text font
        'headline': ['Oswald', 'sans'], // Use 'Oswald' for headlines
        'cta': ['Oswald', 'sans'], // Use 'Oswald' for call to action buttons
      },
      colors: {
        'primary': '#008080',
        'secondary': '#13324E',
        'accent': '#DE5529',
        'neutral': '#A80000',
        'base-100': '#F9FBDF'  
      },
    },
  },
  plugins: [require("daisyui")],

};
