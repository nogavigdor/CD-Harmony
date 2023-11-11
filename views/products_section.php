<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out relative">
            <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->image_name) ?>" alt="<?= $product->product_title ?>" class="w-full h-40 object-cover rounded-md">
            <!-- Ribbon for Release Date - if exists-->
                 <?php if (isset($product->release_date) && !empty($product->release_date)) : ?>
                   
                    <div class="absolute top-0 left-0 h-30 bg-secondary p-4  flex items-center justify-center text-white">
                    <?= $product->release_date ?>
                    
                     </div>
                 <?php endif; ?>
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
