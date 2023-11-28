<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg w-full sm:w-auto transition transform hover:-translate-y-1">
            <div class="p-4">
                <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->main_image) ?>" alt="Product Image" class="w-full h-40 object-cover rounded-md">
                <h2 class="text-lg font-semibold mt-4"><?= htmlspecialchars($product->product_title) ?></h2>

                <p class="mt-2">Artist: <?= htmlspecialchars($product->artist_title) ?></p> <!-- Use $product->artist_title for the artist's name -->

                <div class="mt-4">
                    <span class="text-xl font-semibold"><?= htmlspecialchars($product->price) ?> USD</span>
                    <?php if ($product->units_in_stock > 0) : ?>
                        <span class="text-xl font-semibold"><?= htmlspecialchars($product->quantaty_in_stock) ?> USD</span>
                        <span class="text-xl font-semibold"><?= htmlspecialchars($product->product_condition) ?> /span>
                        <button class="btn btn-primary mt-2 w-20" onclick="addToCart(<?= $product->product_id ?>)">Add to Cart</button>
                        <button class="btn btn-secondary mt-2" onclick="addToWishlist(<?= $product->product_id ?>)">Add to Wishlist</button>
                    <?php else : ?>
                        <p class="text-red-500 mt-2">Out of Stock</p>
                        <button class="btn btn-secondary mt-2" onclick="addToWishlist(<?= $product->product_id ?>)">Add to Wishlist</button>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/product/<?= $product->product_id ?>" class="btn btn-primary mt-2">View Details</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
