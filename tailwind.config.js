module.exports = {
  content: [
    './src/**/*.{html,css,js}', 
    './*.php',
    './views/*.php',
    './views/**/*.php',
    './partials/*.php',
   // "./node_modules/tw-elements/dist/js/**/*.js"
  ],
  //plugins: [require("tw-elements/dist/plugin.cjs")],
  darkMode: "class",
  theme: {
    extend: {
      fontFamily: {
        'body': ['Poppins', 'sans'], // Use 'Poppins' for the default text font
        'headline': ['Oswald', 'sans'], // Use 'Oswald' for headlines
        'cta': ['Oswald', 'sans'], // Use 'Oswald' for call to action buttons
      },
      text: {
        'cta': '#fff', // Use 'Oswald' for call to action buttons
      },
      colors: {
        'primary': '#008080',
        'secondary': '#13324E',
        'accent': '#DE5529',
        'neutral': '#A80000',
        'base-100': '#F9FBDF',
        'placeholder': 'gray-400', 
        'buttonText':'white',
      },
    },
  },
  daisyui: {
    themes: [
      {
        mytheme: {
        
          'primary': '#008080',
          'secondary': '#13324E',
          'accent': '#DE5529',
          'neutral': '#A80000',
          'base-100': '#F9FBDF',
          'placeholder': 'gray-400', 
          'buttonText':'white',
                    
            "info": "#ffffff",
                    
            "success": "#00ffff",
                    
            "warning": "#ffffff",
                    
            "error": "#ffffff",
        },
      },
    ],
  },
  plugins: [
    require('daisyui'),
    
  ],
  darkMode: "class"
};


