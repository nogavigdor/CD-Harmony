
<div class="flex justify-between">
    <?php
    //Displays recommended products on the product details page
    $controller = new Controllers\RecommendationController();
    $recommendedProducts = $controller->getRecommendationsOnProductPage($product->product_id);
    foreach ($recommendedProducts as $recommendedProduct) {
        ?>
        <div class="w-1/3">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="flex justify-between">
                    <div class="flex flex-col">
                        <h3 class="text-xl font-semibold mb-2"><?= $recommendedProduct['product_title'  ] ?></h3>
                        <p class="text-sm text-gray-600"><?= $recommendedProduct['artist_title'] ?></p>
                    </div>
                    
                </div>
                <div class="flex justify-center">
                    <img src="<?= BASE_URL . '/src/assets/images/albums/' . htmlspecialchars($recommendedProduct['image_name']) ?>" alt="Product Image" class="w-1/2 object-cover">
                </div>
                <div class="flex justify-center">
                    <a href="<?= BASE_URL . '/product/' . $recommendedProduct['product_id'] ?>" class="btn btn-accent w-1/2">View Product</a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
   
  
</div>
