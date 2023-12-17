
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

  document.querySelector('.btn.btn-secondary').addEventListener('click', function(event) {
    event.preventDefault();

    // Update cart item count
    document.getElementById('cartItemCount').textContent = /* Get cart item count from your server or local storage */;

    // Show cart slider
    document.getElementById('cartSlider').style.transform = 'translateX(0)';

    // Update cart items and total price
    document.getElementById('cartItems').innerHTML = /* Get cart items from your server or local storage */;
    document.getElementById('totalPrice').textContent = /* Get total price from your server or local storage */;
});

});


