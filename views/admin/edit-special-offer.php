<?php use Controllers\ProductController;
use Services\SessionManager;
$csrfToken=SessionManager::generateCSRFToken();
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
            <form action="<?= BASE_URL.'/admin/special-offer/update' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
                <input type="hidden" name="csrfToken" value="<?= $csrfToken; ?>"> 
                <input type="hidden" name="_method" id="_method" value="PUT">
                <h3 class="mb-4 text-2xl font-semibold text-gray-700">Edit Special Offer</h3>
                <h3 class="text-blue-900"><?= htmlspecialchars($offerDetails['condition_title'] ?? ''); ?> Variant</h3>
                <h3 class="mb-4"><?= "Product Title: " . htmlspecialchars($offerDetails['product_title'] ?? '');  ?></h3> 
                <h3 class="mb-4"><?= 'Artist Name: ' . htmlspecialchars($offerDetails['artist_title'] ?? ''); ?></h3>
                <!--Image Preview -->
                <div class="mb-4">
                    <img class="h-48" src="<?= BASE_URL . '/src/assets/images/albums/'. htmlspecialchars($offerDetails['image_name'] ?? ''); ?>" alt="Current Image" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <h3 class="text-blue-900"><?= htmlspecialchars($offerDetails['condition_title'] ?? ''); ?> Variant</h3>
                <!-- Special Offer Title -->
                <div class="mb-4">
                    <label for=specialOfferTitle" class="block text-sm font-medium text-gray-700">Special Offer Title:</label>
                    <input type="text" name="specialOfferTitle" id="specialOfferTitle" value="<?= htmlspecialchars($offer['title'] ?? ''); ?>"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <!-- Description -->
                <div class="mb-4">
                    <label for="specialOfferDescription" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="specialOfferDescription" id="specialOfferDescription"
                        class="h-36 mt-1 p-2 block w-full  rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  auto"><?= htmlspecialchars($offer['special_offer_description'] ?? ''); ?></textarea>
                </div>

                
                <!-- Start Date -->
                <div class="mb-4">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" name="startDate" id="startDate" value="<?= $offer['special_offer_start_date'] ?? ''; ?>"
                        class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                  <!-- End Date -->
                  <div class="mb-4">
                    <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" name="endDate" id="endDate" value="<?= $offer['special_offer_end_date'] ?? ''; ?>"
                        class="h-8 mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>


                    <div class="mb-4">
                      
                        <p><?="Price: " . $offerDetails['price'] ?? ''; ?> DKK</p>
                <!-- Discount Sum -->
                <div class="mb-4">
                    <input type="hidden" name="specialOfferId" id="specialOfferId" value="<?= $offer['special_offer_id'] ?? ''; ?>">
                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount Sum:</label>
                    <input type="number" name="discount" id="discount" value="<?= $offer['discount_sum'] ?? ''; ?>"
                        class="h-8 w-full mt-1 p-2 block   rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                           <input type="hidden" name="is_homepage" id="is_homepage" value="<?= $offer['is_homepage'] ?? ''; ?>">
                <input class="btn" id="SubmitUpdateSpecialOfferForm" type="submit" value="Update Special Offer"/>
              
            </form>
        </div>
    </div>
</main>
<script>
   document.getElementById('SubmitUpdateSpecialOfferForm').addEventListener('click', async function (e) {
    e.preventDefault();
    let csrfToken = document.querySelector('input[name="csrfToken"]').value;
    let specialOfferTitle = document.getElementById('specialOfferTitle').value;
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    let specialOfferDescription = document.getElementById('specialOfferDescription').value;
    let discount = document.getElementById('discount').value;
    let specialOfferId = document.getElementById('specialOfferId').value;
    let _method = document.getElementById('_method').value;
    let isHomepage = document.getElementById('is_homepage').value;
    let formData = new FormData();
    formData.append('csrfToken', csrfToken);
    formData.append('specialOfferTitle', specialOfferTitle);
    formData.append('startDate', startDate);
    formData.append('endDate', endDate);
    formData.append('specialOfferDescription', specialOfferDescription);
    formData.append('discount', discount);
    formData.append('isHomepage', isHomepage);
    formData.append('specialOfferId', specialOfferId);
    formData.append('_method', _method);


    try {
        const response = await fetch('<?php echo BASE_URL.'/admin/special-offer/update' ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
                alert('Special Offer was updated successfully');
                window.location.href = '<?php echo BASE_URL.'/admin/special-offers' ?>';
            } else {
                if (data && Object.keys(data).length > 0) {
                    let errorMessage = '';
                    for (let key in data) {
                        errorMessage += data[key] + '\n';
                    }
                    alert('Special Offer could not be updated:\n' + errorMessage);
                }
            } 
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    }
});


    
    </script>

<?php include 'admin-footer.php' ?>
