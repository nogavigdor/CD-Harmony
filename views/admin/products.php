<?php use Controllers\ProductController;
use Services\SessionManager;
?>
<?php include 'admin-header.php' ?>

<main class='content bg-primary'>


    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            <?php
            $products = new ProductController();
            $productsList = $products->getProductsList(); 

            if (!empty($productsList)) {
            ?>
                <h2 class="text-2xl font-semibold mb-4">Product List</h2>
                <a href="<?php echo BASE_URL.'/admin/product/add/' ?>" class="btn">Add Product</a>
                <div class="grid grid-cols-5 gap-4">
                    <!-- Column headers -->
                    <div class="font-bold text-gray-700">Product ID</div>
                    <div class="font-bold text-gray-700">Condition</div>
                    <div class="font-bold text-gray-700">Title</div>
                    <div class="font-bold text-gray-700">Price</div>
                    <div class="font-bold text-gray-700">Actions</div>

                    <!-- Product items -->
                    <?php foreach ($productsList as $product) { ?>
                        <div class="border p-4 rounded-md">
                            <div><?= $product->product_id ?></div>
                            <div><?= $product->condition_title  ?></div>
                            <div><?= $product->product_title ?></div>
                            <div><?= $product->condition_id == 1 ? $product->new_price : $product->used_price ?></div>
                            <div class="flex space-x-4">
                                <a href="<?= BASE_URL . '/admin/products/update/' . $product->product_id ?>" class="btn btn-primary">Update</a>
                                <a href="<?= BASE_URL . '/admin/products/delete/' . $product->product_id ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No products found.</p>
            <?php } ?>
        </div>
    </div>
</main>

<?php include 'admin-footer.php' ?>
