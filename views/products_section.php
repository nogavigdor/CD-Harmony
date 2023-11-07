<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out">
        <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->image_name) ?>" alt="<?php echo $product->product_title ?>" class="w-full h-40 object-cover rounded-md">
            <div class="p-4">
                <h2 class="text-lg font-semibold"><?= htmlspecialchars($product->product_title) ?></h2>
                <p class="mt-2"><?= htmlspecialchars($product->artist_title) ?></p>
                <p class="mt-2"><?= htmlspecialchars($product->tag_title) ?></p>
                <div class="mt-4">
                    <!-- Display New Price -->
                    <?php if ($product->new_quantity > 0) : ?>
                        <div class="text-lg font-semibold text-green-500"><?= $product->new_price ?> USD (New)</div>
                    <?php endif; ?>
                    <!-- Display Used Price -->
                    <?php if ($product->old_quantity > 0) : ?>
                        <div class="text-lg font-semibold text-red-500"><?= $product->old_price ?> USD (Used)</div>
                    <?php endif; ?>
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/product/<?= $product->product_id ?>" class="btn btn-primary w-full">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
