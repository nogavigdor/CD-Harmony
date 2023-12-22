<?php use Controllers\ProductController;
use Services\SessionManager;
SessionManager::generateCSRFToken();
?>
<?php include 'admin-header.php' ?>

<main class='content bg-primary'>


    <div class="flex bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
        <?php
        ?>
          <form action="<?php echo BASE_URL.'/admin/product/update' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">  

                <!-- Title -->
                <div class="mb-4">
                    <label for="productTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="productTitle" id="productTitle" value="<?= htmlspecialchars($productDetails->product_title) ?? ''; ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                   <!-- Title -->
                   <div class="mb-4">
                    <label for="artistTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="artistTitle" id="artistTitle" value="<?= htmlspecialchars($productDetails->artist_title) ?? ''; ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="descriptionTitle" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="descriptionTitle" id="descriptionTitle"
                        class="h-72 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"><?= htmlspecialchars($productDetails->product_description) ?? ''; ?></textarea>
                </div>

                <!-- Product Condition (CD) Fields -->
                <div id="cdFields">
                    <div class="mb-4">
                        <label for="quantityInStock" class="block text-sm font-medium text-gray-700">Quantity in Stock:</label>
                        <input type="text" name="quantityInStock" id="quantityInStock" value="<?= $productDetails->quantity_in_stock ?? ''; ?>"
                            class="mt-1 h-8 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="text" name="price" id="price" value="<?= $productDetails->price ?? ''; ?>"
                            class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Current Image Preview -->
                <div class="mb-4">
                    <label for="currentImage" class="block text-sm font-medium text-gray-700">Current Image:</label>
                    <img h-96 src="<?= BASE_URL . '/src/assets/images/albums/'. htmlspecialchars($productDetails->image_name) ?? ''; ?>" alt="Current Image" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="updateImage" class="block text-sm font-medium text-gray-700">Upload New Image:</label>
                    <input type="file" name="UpdateImage" id="updateImage"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                 <input type="hidden" name="product_id" value="<?= $productDetails->product_id ?? ''; ?>">
                 
            </form>
        </div>
    </div>
</main>

<?php include 'admin-footer.php' ?>
