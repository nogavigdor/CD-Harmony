<?php include 'admin-header.php' ?>
<?php
use Services\SessionManager; 
use Models\ProductModel;
?>
<main class='content bg-primary text-base-100'>

<h1>Products Page</h1>
<?php $productsModel=new ProductModel();
     $products=$productsModel->getAllProducts();
?>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out relative">
            <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->image_name) ?>" alt="<?= $product->product_title ?>" class="w-full h-40 object-cover rounded-md">
            
            <!-- Ribbon for Release Date - if exists-->
            <?php if (isset($product->release_date) && !empty($product->release_date)) : ?>
                <div class="absolute top-0 left-0 h-30 bg-secondary p-4 flex items-center justify-center text-white">
                    <?= $product->release_date ?>
                </div>
            <?php endif; ?>
            
            <div class="p-4">
                <h2 class="text-lg text-secondary font-semibold"><?= htmlspecialchars($product->product_title) ?></h2>
                <p class="mt-2 text-lg text-secondary"><?= htmlspecialchars($product->artist_title) ?></p>
                <p class="mt-2"><?= htmlspecialchars($product->tag_title) ?></p>
                
                <div class="mt-4">
                    <!-- Display New Price -->
                    <?php if ($product->new_quantity > 0) : ?>
                        <div class="text-lg text-secondary"><?= $product->new_price ?> Kr (New)</div>
                    <?php endif; ?>
                    
                    <!-- Display Used Price -->
                    <?php if ($product->used_quantity > 0) : ?>
                        <div class="text-lg text-secondary"><?= $product->used_price ?> Kr (Used)</div>
                    <?php endif; ?>
                    
                    <!-- View Details Button -->
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/product/<?= $product->product_id ?>" class="btn btn-secondary w-full text-white">View Details</a>
                    </div>
                    
                    <!-- Edit and Delete Buttons -->
                    <div class="flex justify-between mt-4">
                        <!-- Edit Button -->
                        <a href="<?= BASE_URL ?>/edit-product/<?= $product->product_id ?>" class="btn btn-primary">Edit</a>
                        
                        <!-- Delete Button (with confirmation prompt) -->
                        <button onclick="confirmDelete(<?= $product->product_id ?>)" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- JavaScript for Delete Confirmation -->
<script>
    function confirmDelete(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = '<?= BASE_URL ?>/delete-product/' + productId;
        }
    }
</script>

</main>
<?php include 'admin-footer.php' ?>
