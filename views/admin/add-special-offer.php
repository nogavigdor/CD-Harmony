<?php use Controllers\ProcductController;
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
                 <!-- Image Preview -->
                <div class="mb-4">
                    <img class="h-48" src="<?= BASE_URL . '/src/assets/images/albums/'. htmlspecialchars($specialOffer['image_name'] ?? ''); ?>" alt="Current Image" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
    
                </div>
                <!--Title -->
                <div class="mb-4">
                    <label for="specialOfferTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="specialOfferTitle" id="specialOfferTitle" value=""
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                  <!-- Description -->
                  <div class="mb-4">
                    <label for="specialOfferDescription" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="specialOfferDescription" id="specialOfferDescription"
                        class="h-36 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"></textarea>
                </div>

                <div>Current price is <?= $specialOffer['price'] ?? ''; ?> DKK</div>
                
                <!-- Discount Sum -->
                
                  
                <div class="mb-4">
                        <label for="discount" class="block text-sm font-medium text-gray-700">Discount sum:</label>
                        <input type="number" name="discount" id="discount" 
                            class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">DKK
                    </div>
                <!-- Start Date -->
                
                <div class="mb-4">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                <input type="date" name="startDate" id="startDate" 
                    class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- End Date -->
                <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                <input type="date" name="endDate" id="endDate" 
                    class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                
                <input type="hidden" name="price" id="price" value="<?= $specialOffer['price'] ?? ''; ?>">
                <input type="hidden" name="variant_id" id="variant_id" value="<?= $specialOffer['product_variant_id'] ?? ''; ?>"> 
                <input class="btn" id="SubmitAddSpecialOfferForm" type="submit" value="Add Special Offer"/>
            </form>
        </div>
    </div>
</main>
<script>
    document.getElementById('SubmitAddSpecialOfferForm').addEventListener('click', async function (e) {
        e.preventDefault();
        let csrfToken = document.querySelector('input[name="csrf_token"]').value;
        let specialOfferTitle = document.getElementById('specialOfferTitle').value;
        let specialOfferDescription = document.getElementById('specialOfferDescription').value;
        let discount = document.getElementById('discount').value;
        let startDate = document.getElementById('startDate').value;
        let endDate = document.getElementById('endDate').value;
        let variantId = document.getElementById('variant_id').value;
        let price = document.getElementById('price').value;

        let formData = new FormData();
        formData.append('csrf_token', csrfToken);
        formData.append('specialOfferTitle', specialOfferTitle);
        formData.append('specialOfferDescription', specialOfferDescription);
        formData.append('discount', discount);
        formData.append('startDate', startDate);
        formData.append('endDate', endDate);
        formData.append('variantId', variantId);
        formData.append('price', price);

        try {
            const response = await fetch('<?php echo BASE_URL.'/admin/product/add-special-offer' ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                alert('Special Offer was created successfully');
                window.location.href = '<?php echo BASE_URL.'/admin/special-offers' ?>';
            } else {
                if (data && Object.keys(data).length > 0) {
                    let errorMessage = '';
                    for (let key in data) {
                        errorMessage += data[key] + '\n';
                    }
                    alert('Special Offer could not be added:\n' + errorMessage);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request');
        }
    });
    
    </script>

<?php include 'admin-footer.php' ?>
