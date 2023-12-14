<?php use Controllers\ProductController;
use Services\SessionManager;
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
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">  

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="title" id="title" value="<?= $productDetails->product_title ?? ''; ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="description" id="description"
                        class="mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-52 auto"><?php echo $productDetails->product_description ?? ''; ?></textarea>
                </div>

                <!-- Product Condition (CD) Fields -->
                <div id="cdFields">
                    <div class="mb-4">
                        <label for="quantityInStock" class="block text-sm font-medium text-gray-700">Quantity in Stock:</label>
                        <input type="text" name="quantityInStock" id="quantityInStock" value="<?php echo $productDetails->quantity_in_stock ?? ''; ?>"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="text" name="price" id="price" value="<?php echo $productDetails->price ?? ''; ?>"
                            class="h-52 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Current Image Preview -->
                <div class="mb-4">
                    <label for="currentImage" class="block text-sm font-medium text-gray-700">Current Image:</label>
                    <img src="<?= BASE_URL . '/src/assets/images/albums/'. htmlspecialchars($productDetails->image_name) ?? ''; ?>" alt="Current Image" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image:</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'admin-footer.php' ?>
