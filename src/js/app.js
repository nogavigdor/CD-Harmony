
// handles login/logout menu/menu visibility
document.addEventListener('DOMContentLoaded', function () {
  console.log('DOM content loaded'); 
  
  const userBtn = document.getElementById('userBtn');
  const menu = document.getElementById('menu');
  
  userBtn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
  });
  
  // Close the menu when clicking outside of it
  window.addEventListener('click', (event) => {
      if (!userBtn.contains(event.target) && !menu.contains(event.target)) {
          menu.classList.add('hidden');
      }
  });

  //document.querySelector('.btn.btn-secondary').addEventListener('click', function(event) {
  ////  event.preventDefault();

    // Update cart item count
 

    // Show cart slider
  //  document.getElementById('cartSlider').style.transform = 'translateX(0)';

    // Update cart items and total price
 
//});

});


