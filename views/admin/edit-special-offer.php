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
            <form action="<?= BASE_URL.'/admin/product/update' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>"> 
                <h3><?= htmlspecialchars($specialOffer['product_title'] ?? '') . ' - ' . htmlspecialchars($specialOffer['artist_title'] ?? ''); ?></h3> 
                <h3 class="text-blue-900"><?= htmlspecialchars($specialOffer['condition_title'] ?? ''); ?> Variant</h3>
                <!-- Product Title -->
                <div class="mb-4">
                    <label for="productTitle" class="block text-sm font-medium text-gray-700">Product Title:</label>
                    <input type="text" name="productTitle" id="productTitle" value="<?= htmlspecialchars($specialOffer['product_title'] ?? ''); ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Artist Title -->
                <div class="mb-4">
                    <label for="artistTitle" class="block text-sm font-medium text-gray-700">Artist Title:</label>
                    <input type="text" name="artistTitle" id="artistTitle" value="<?= htmlspecialchars($specialOffer['artist_title'] ?? ''); ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Start Date -->
                <div class="mb-4">
                    <label for="releaseDate" class="block text-sm font-medium text-gray-700">Release Date:</label>
                    <input type="date" name="releaseDate" id="releaseDate" value="<?= $specialOffer['release_date'] ?? ''; ?>"
                        class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="productDescription" id="productDescription"
                        class="h-72 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"><?= htmlspecialchars($specialOffer['product_description'] ?? ''); ?></textarea>
                </div>

                <!-- Product Variant Fields -->
                <div id="cdFields">
                    <div class="mb-4">
                        <label for="quantityInStock" class="block text-sm font-medium text-gray-700">Quantity in Stock:</label>
                        <input type="number" name="quantityInStock" id="quantityInStock" value="<?= $specialOffer['quantity_in_stock'] ?? ''; ?>"
                            class="mt-1 h-8 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="text" name="price" id="price" value="<?= $specialOffer['price'] ?? ''; ?>"
                            class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Tags list -->
                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags (please edit with comma and no space gaps):</label>
                    <textarea   class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="tags" id="tags"><?= $specialOffer['tag_titles'] ?? ''; ?></textarea>
                </div>
                
                <!-- Current Image Preview -->
                <div class="mb-4">
                    <label for="currentImage" class="block text-sm font-medium text-gray-700">Current Image:</label>
                    <img h-96 src="<?= BASE_URL . '/src/assets/images/albums/'. htmlspecialchars($specialOffer['image_name'] ?? ''); ?>" alt="Current Image" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image:</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <input class="btn" id="SubmitUpdateProductForm" type="submit" value="Update Product"/>
                <input type="hidden" name="product_id" id="product_id" value="<?= $specialOffer['product_id'] ?? ''; ?>">
                <input type="hidden" name="variant_id" id="variant_id" value="<?= $specialOffer['product_variant_id'] ?? ''; ?>">
            </form>
        </div>
    </div>
</main>
<script>
   document.getElementById('SubmitUpdateProductForm').addEventListener('click', async function (e) {
    e.preventDefault();
    let csrfToken = document.querySelector('input[name="csrf_token"]').value;
    let productTitle = document.getElementById('productTitle').value;
    let artistTitle = document.getElementById('artistTitle').value;
    let releaseDate = document.getElementById('releaseDate').value;
    let productDescription = document.getElementById('productDescription').value;
    let quantityInStock = document.getElementById('quantityInStock').value;
    let price = document.getElementById('price').value;
    let tags = document.getElementById('tags').value;
    let image = document.getElementById('image').files[0];
    let productId = document.getElementById('product_id').value;
    let variantId = document.getElementById('variant_id').value;
    let formData = new FormData();
    formData.append('csrf_token', csrfToken);
    formData.append('productTitle', productTitle);
    formData.append('artistTitle', artistTitle);
    formData.append('releaseDate', releaseDate);
    formData.append('productDescription', productDescription);
    formData.append('quantityInStock', quantityInStock);
    formData.append('price', price);
    formData.append('tags', tags);
    formData.append('image', image);
    formData.append('productId', productId);
    formData.append('variantId', variantId);

    try {
        const response = await fetch('<?php echo BASE_URL.'/admin/product/update' ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
            alert('Product was updated successfully');
            window.location.href = '<?php echo BASE_URL.'/admin/products' ?>';
        } else {
            alert('Product could not be edited since ' + data.image);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    }
});


    
    </script>

<?php include 'admin-footer.php' ?>
