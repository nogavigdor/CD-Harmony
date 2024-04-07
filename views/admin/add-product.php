<?php use Controllers\ProductController;
use Services\SessionManager;
$csrfToken =SessionManager::generateCSRFToken();
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
          <form action="<?php echo BASE_URL.'/admin/product/add' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">  
                <h3 class="text-2xl font-semibold text-gray-700">Add Product Variant</h3>
                <!--Product Title -->
                <div class="mb-4">
                    <label for="productTitle" class="block text-sm font-medium text-gray-700">Product Title:</label>
                    <input type="text" name="productTitle" id="productTitle" value=""
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                   <!-- Artist Title -->
                   <div class="mb-4">
                    <label for="artistTitle" class="block text-sm font-medium text-gray-700">Artist Title:</label>
                    <input type="text" name="artistTitle" id="artistTitle" value=""
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                 <!-- Release Date -->
                 <div class="mb-4">
                <label for="releaseDate" class="block text-sm font-medium text-gray-700">Release Date:</label>
                <input type="date" name="releaseDate" id="releaseDate" 
                    class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                    

                <!-- Description -->
                <div class="mb-4">
                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="productDescription" id="productDescription"
                        class="h-72 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"></textarea>
                </div>
              
                <!-- Product Condition -->
                <div class="mb-4">
                    <label for="productCondition" class="block text-sm font-medium text-gray-700">Product Condition:</label>
                    <select name="productCondition" id="productCondition" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1">New</option>
                        <option value="2">Used</option>
                    </select>
                </div>
                <!-- Stock  -->
                <div id="cdFields">
                    <div class="mb-4">
                        <label for="quantityInStock" class="block text-sm font-medium text-gray-700">Quantity in Stock:</label>
                        <input type="number" name="quantityInStock" id="quantityInStock" min="0" value=""
                            class="mt-1 h-8 p-2 block w-full rounded-md border-gray-30 0 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                           
                 <p class="mt-2 text-sm text-red-600"></p>
               
                                </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="text" name="price" id="price" value=""
                            class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <!-- Tags list -->
                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Please enter tags with comma seperated:</label>
                    <textarea   class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    name="tags" id="tags"></textarea>
                      
                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="updateImage" class="block text-sm font-medium text-gray-700">Upload New Image (up to 1 mb):</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <input class="btn" id="SubmitAddProductForm" type="submit" value="Add Product"/>
            </form>
        </div>
    </div>
</main>
<script>
document.getElementById('SubmitAddProductForm').addEventListener('click', function (e) {
    e.preventDefault();
    let csrfToken = document.querySelector('input[name="csrf_token"]').value;
    let productTitle = document.getElementById('productTitle').value;
    let artistTitle = document.getElementById('artistTitle').value;
    let releaseDate = document.getElementById('releaseDate').value;
    let productDescription = document.getElementById('productDescription').value;
    let productCondition = document.getElementById('productCondition').value;
    let quantityInStock = document.getElementById('quantityInStock').value;
    let price = document.getElementById('price').value;
    let tags = document.getElementById('tags').value;
    let image = document.getElementById('image').files[0];
    let formData = new FormData();
    formData.append('csrf_token', csrfToken);
    formData.append('productTitle', productTitle);
    formData.append('artistTitle', artistTitle);
    formData.append('releaseDate', releaseDate);
    formData.append('productDescription', productDescription);
    formData.append('productCondition', productCondition);
    formData.append('quantityInStock', quantityInStock);
    formData.append('price', price);
    formData.append('tags', tags);
    formData.append('image', image);
    fetch('<?php echo BASE_URL.'/admin/product/add' ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Product added successfully');
                window.location.href = '<?php echo BASE_URL.'/admin/products' ?>';
            } else {
                if (data && Object.keys(data).length > 0) {
                    let errorMessage = '';
                    for (let key in data) {
                        errorMessage += data[key] + '\n';
                    }
                    alert('Product could not be added:\n' + errorMessage);
                }
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('There was an error adding the product. Please try again later.');
        });
});


    
    </script>

<?php include 'admin-footer.php' ?>

