<?php 
use Services\SessionManager;
use Controllers\ProductController;
use Models\SpecialOfferModel;
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
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Artist Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Special Offer Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Special Offer Description</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($specialOffers as $offer){
                        $specialOfferModel = new SpecialOfferModel();
                        $offerDetails = $specialOfferModel->getSpecialOfferDetails($offer['product_variant_id']);
                    ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offerDetails['product_variant_id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offerDetails['product_title']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offer['title']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offer['special_offer_description']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($offer['special_offer_start_date']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="radio" name="homepage" value="<?= htmlspecialchars($offer['product_variant_id']) ?>" <?= $offer['is_homepage'] ? 'checked' : '' ?> onchange="updateHomepage(this.value)">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700">Delete</button>
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
    fetch('special-offer/update-homepage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_variant_id=' + productVariantId,
    })
    .then(response => response.text())
    .then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });
}
</script>
<?php include 'admin-footer.php' ?>
