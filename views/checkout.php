<?php use Services\SessionManager;
use Controllers\CartController;
use Controllers\ProductController;

?>
<?php include 'header.php'; ?>

<!-- Main content section -->
<main class="container mx-auto p-4 bg-white mt-8 min-h-screen">
    <h1 class="text-2xl headline text-secondary font-bold mb-4">Your Cart</h1>
    <div class="flex mb-4">
    <a href="<?= BASE_URL.'/cart'; ?>" class="btn flex-1 text-center py-2 m-2">Back to cart</a>  
        <a href="<?= BASE_URL.'/order-confirmation' ?>"class="btn  flex-1 text-center py-2 m-2 ">Place your order</a>
    </div>
    <?php
    // Iterate through cart items and display each one
    if (SessionManager::isVar('cart')) {
       // $cart = SessionManager::getSessionVariable('cart');
        $sub_total=0;   
        $discount_total=0;
        $grand_total=0;

    //to keet the cart details accuracy, I'll only be using the quantiy from the cart session
    //and the other data I'll pull from the database and not from the cart session
    //in order to have the most accurrate data
    foreach ($cart as $product_var_id => $cartItem) {
        if (is_array($cartItem) && isset($cartItem['quantity'])) {
        $quantity=$cartItem['quantity'];
       
            ?>
<div class="grid grid-cols-4 gap-4 items-center border-b border-gray-300 py-2">
  <img src="<?= BASE_URL.IMAGE_PATH.$cartItem['image']; ?>" alt="<?= $cartItem['product_title'] ?>" class="w-16 h-16 object-cover mr-4">
  <div class="grid col-span-2  grid-cols-2 gap-4">
    <p class="text-lg font-semibold"><?= $cartItem['product_title']?></p>
    <p class="text-lg font-semibold"><?= $cartItem['condition']?></p>
    <p class="text-lg font-semibold"><?= $cartItem['artist_title']?></p>
    <p class="text-gray-600">Quantity: <?= $quantity ?>
    <span class="text-red-700" id="err__<?= $product_var_id ?>"></span></p>
    <p class="text-gray-600">Stock: <?= $cartItem['quantity_in_stock']?></p>
    <div class="flex">
        <input type="hidden" value="<?= $cartItem['quantity_in_stock']?>" id="stk__<?= $product_var_id ?>" name="stk__<?= $product_var_id ?>" />
        <span id="err__<?= $product_var_id ?>"></span>
    </div>

    <p class="text-gray-600">Price: <?= number_format($cartItem['price'], 2) ?> DKK</p>
    <?php if ($cartItem['discount'] > 0): ?>
      <p class="text-gray-600">Discount: <?= $cartItem['discount'] ?></p>
    <?php endif; ?>
  </div>
  <div class="flex flex-col gap-y-4">
    <!-- Add your update and delete logic here -->
    
  </div>
</div>
            <?php
        }
        }
    } else {
        // If the cart is empty
        echo "<p>Your cart is empty.</p>";
    }
    ?>

   
    </main>
  


<?php include 'footer.php'; ?>