<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (Same as before) ... -->
</head>
<body class="bg-gray-100">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Products with Tag: <?php echo htmlspecialchars($tag); ?></h1>
        <?php if (count($products) > 0) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($products as $product) : ?>
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4">
                            <img src="<?php echo BASE_URL."src/assets/images/albums/" .htmlspecialchars($product['main_image']); ?>" alt="Product Image" class="w-full h-40 object-cover rounded-md">
                            <h2 class="text-lg font-semibold mt-4"><?php echo htmlspecialchars($product['title']); ?></h2>
                          
                            <div class="mt-4">
                                <span class="text-xl font-semibold"><?php echo htmlspecialchars($product['price']); ?> USD</span>
                                <?php if ($product['units_in_stock'] > 0) : ?>
                                    <?php if ($product['cd_condition'] === 'new' || $product['cd_condition'] === 'used') : ?>
                                        <button class="block mt-2 bg-green-500 text-white rounded-md px-4 py-2 hover:bg-green-600" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
                                    <?php endif; ?>
                                    <button class="block mt-2 text-blue-600 hover:underline" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">Add to Wishlist</button>
                                <?php else : ?>
                                    <p class="text-red-500 mt-2">Out of Stock</p>
                                    <button class="block mt-2 text-blue-600 hover:underline" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">Add to Wishlist</button>
                                <?php endif; ?>
                                <a href="/product/<?php echo $product['product_id']; ?>" class="block mt-2 text-blue-600 hover:underline">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="text-lg text-gray-600">No products found with this tag.</p>
        <?php endif; ?>
    </div>

    <script>
        function addToCart(productId) {
            // Add logic to add the product to the cart
            console.log('Added product to cart:', productId);
        }

        function addToWishlist(productId) {
            // Add logic to add the product to the wishlist
            console.log('Added product to wishlist:', productId);
        }
    </script>
</body>
</html>
