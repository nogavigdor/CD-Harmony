<!DOCTYPE html>
<html lang="en">
<?php
    include 'header.php'
?>
<body class="bg-F9FBDF">
    <!-- Header section -->
    <header class="bg-008080 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>"><img src="./src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>
            <nav class="space-x-4">
                <a href="#" class="text-white">Home</a>
                <a href="#" class="text-white">Shop</a>
                <a href="#" class="text-white">About</a>
                <a href="#" class="text-white">Contact</a>
            </nav>
        </div>
        <div class="container mx-auto flex justify-between items-center">
            <div>
                &copy; <?php echo date("Y"); ?> CD Harmony. All rights reserved.
            </div>
            <div>
                <button class="btn btn-primary">Shopping Cart</button>
                <button class="btn btn-secondary">Log In</button>
                <button class="btn btn-emphasis">Sign Up</button>
            </div>
        </div>
    </header>

    <!-- Main content section -->
 <!-- Main content section -->
<!-- Main content section -->
<main class="container mx-auto p-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-lg w-full sm:w-auto transition transform hover:-translate-y-1">
            <div class="p-4">
                <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->main_image) ?>" alt="Product Image" class="w-full h-40 object-cover rounded-md">
                <h2 class="text-lg font-semibold mt-4"><?= htmlspecialchars($product->title) ?></h2>

                <!-- Product Description -->
                <p class="mt-2"><?= htmlspecialchars($product->product_description) ?></p>

                <!-- Quantity Field with Plus and Minus Buttons -->
                <div class="mt-4">
                    <span class="text-xl font-semibold"><?= htmlspecialchars($product->price) ?> USD</span>
                    <form method="post" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product->product_id ?>">
                        <div class="quantity-input mt-2">
                            <button class="btn btn-secondary" type="submit" name="decrease_quantity">-</button>
                            <input type="number" name="quantity" value="1">
                            <button class="btn btn-secondary" type="submit" name="increase_quantity">+</button>
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <?php if ($product->units_in_stock > 0) : ?>
                        <button class="btn btn-primary" type="submit" form="cart_form">Add to Cart</button>
                        <button class="btn btn-secondary" type="submit" form="wishlist_form">Add to Wishlist</button>
                    <?php else : ?>
                        <p class="text-red-500 mt-2">Out of Stock</p>
                        <button class="btn btn-secondary" type="submit" form="wishlist_form">Add to Wishlist</button>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL ?>/product/<?= $product->product_id ?>" class="btn btn-primary mt-2">View Details</a>
                </div>
            </div>
        </div>
    </div>
</main>



    <?php include 'footer.php'; ?>
</body>
</html>
