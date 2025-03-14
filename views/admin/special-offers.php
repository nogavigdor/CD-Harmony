    <?php 
    use Services\SessionManager;
    use Controllers\ProductController;
    use Models\SpecialOfferModel;

    $csrfToken = SessionManager::generateCSRFToken();
    ?>

    <?php include 'admin-header.php' ?>

    <main class='flex  bg-gray-100'>
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Product Variant ID</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Product title</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Special Offer Title</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">On Homepage</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($specialOffers as $offer){
                            $specialOfferModel = new SpecialOfferModel();
                            $offerDetails = $specialOfferModel->getSpecialOfferDetails($offer['product_variant_id']);

                            // Check if the current date falls within the offer's date range
                            $currentDate = date('Y-m-d');
                            $startDate = $offer['special_offer_start_date'];
                            $endDate = $offer['special_offer_end_date'];
                            $dateInRange = ($currentDate >= $startDate && $currentDate <= $endDate);
                            
                            // Determine the tooltip message based on whether the current date is within the offer's date range
                            $tooltipMessage = $dateInRange ? '' : 'update dates range to be eligible for homepage';
                                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offerDetails['product_variant_id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars_decode($offerDetails['product_title']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars_decode($offer['title']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offer['special_offer_start_date']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offer['special_offer_end_date']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Only allow selection if the current date is within the offer's date range -->
                                    <input type="radio" name="homepage" value="<?= htmlspecialchars($offer['product_variant_id']) ?>" data-specialid="<?= htmlspecialchars($offer['special_offer_id']) ?>" <?= $offer['is_homepage'] ? 'checked' : '' ?> <?= $dateInRange ? '' : 'disabled' ?> onchange="updateHomepage(this.value)" title="<?= htmlspecialchars($tooltipMessage) ?>">

                                    <?php if (!$dateInRange) { ?>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($tooltipMessage) ?></span>
                                    <?php } ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="<?= BASE_URL . '/admin/special-offers/edit/' . htmlspecialchars($offer['special_offer_id']) ?>" class="btn btn-primary">Edit</a>
                                <form action="<?= BASE_URL . '/admin/special-offers/delete/'; ?>" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="special_offer_id" value="<?= $offer['special_offer_id'];  ?>">
                                    <input 
                                        id="deleteButton<?= $offer['special_offer_id'] ?>"
                                        type="submit" class="btn btn-danger" value="delete"
                                    >
                                </form>
                            </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
        function updateHomepage(productVariantId) {

            fetch(BASE_URL+'/admin/special-offers/update-homepage', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_variant_id=' + encodeURIComponent(productVariantId),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    alert('Homepage special offer was updated successfully');
                  
                } else {
                    alert('Failed to update homepage with special offer. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
             
            });
        }

    </script>


    <?php include 'admin-footer.php' ?>
