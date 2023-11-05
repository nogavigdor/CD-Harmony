<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./dist/output.css">
    <title>Document</title>

</head>
<body class="bg-gray-100">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Products with Tag: <?= htmlspecialchars($tag) ?></h1>
        <?php if (count($products) > 0) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($products as $product) : ?>
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-4">
                            <img src="<?= htmlspecialchars($product['main_image']) ?>" alt="Product Image" class="w-full h-40 object-cover rounded-md">
                            <h2 class="text-lg font-semibold mt-4"><?= htmlspecialchars($product['title']) ?></h2>
                            <p class="text-gray-600 mt-2"><?= htmlspecialchars($product['product_description']) ?></p>
                            <div class="mt-4">
                                <span class="text-xl font-semibold"><?= htmlspecialchars($product['price']) ?> USD</span>
                                <?php if ($product['units_in_stock'] > 0) : ?>
                                    <?php if ($product['cd_condition'] === 'new' || $product['cd_condition'] === 'used') : ?>
                                        <button class="block mt-2 bg-green-500 text-white rounded-md px-4 py-2 hover:bg-green-600" onclick="addToCart(<?= $product['product_id'] ?>)">Add to Cart</button>
                                    <?php endif; ?>
                                    <button class="block mt-2 text-blue-600 hover:underline" onclick="addToWishlist(<?= $product['product_id'] ?>)">Add to Wishlist</button>
                                <?php else : ?>
                                    <p class="text-red-500 mt-2">Out of Stock</p>
                                    <button class="block mt-2 text-blue-600 hover:underline" onclick="addToWishlist(<?= $product['product_id'] ?>)">Add to Wishlist</button>
                                <?php endif; ?>
                                <a href="/product/<?= $product['product_id'] ?>" class="block mt-2 text-blue-600 hover:underline">View Details</a>
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
