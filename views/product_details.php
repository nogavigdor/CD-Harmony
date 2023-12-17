<?php
use Controllers\ProductController;
    include_once 'header.php';
?>

<div class="container min-h-screen mx-auto px-4 sm:px-6 lg:px-8 sm:mb-16 sm:mt-16"  >
    <!-- Product Card -->
             <div class="bg-white shadow-lg p-4 flex-col  flex md:flex-row mx-auto  ">
                <div class="mr-4 md:w-1/3   ">
                  <img  class="w-full rounded-md object-contain md:object-cover"  src="<?= BASE_URL ?>/src/assets/images/albums/<?php echo htmlspecialchars($product->image_name) ?>" alt="Product Image">
                </div>
            
                <div class="mt-4 md:w-2/3">
                    <h2 class="text-2xl font-semibold mt-2"><?=$product->product_title ?></h2>
                    <p class="text-sm text-gray-600"><?php echo $product->artist_title ?></p>
                    <p class="mt-2"><?php echo $product->product_description ?></p>
                    <div class="text-lg font-semibold mb-2">Price:</div>
                    <div>
                        <?php
                    
                        $controller = new ProductController();
                        $productVars = $controller->getProductVariantDetails($product->product_id);
                       
                        // Creates an array of variants, keyed by the condition
                        $variants = [];
                        // Loops through the variants and add them to the array
                        foreach ($productVars as $productVar) {
                            $variants[$productVar['condition_title']] = $productVar;
                        }
                            ?>
                     </div>
                   
                    <div>
                        <form class="add-to-cart-form flex items-center">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <!-- Product condition selection -->
                            <!-- New variant -->
                            <?php if (isset($variants['new'])): ?>
                                <input type="radio" checked class="radio radio-primary" name="condition" value="<?php echo $variants['new']['product_variant_id']; ?>" id="new" class="mr-1" <?php echo $variants['new']['quantity_in_stock'] > 0 ? '' : 'disabled'; ?>>
                                <label for="new">New: <?php echo $variants['new']['price']; ?>
                                    <?php if ($variants['new']['quantity_in_stock'] == 0): ?>
                                        <span class="text-gray-800">(Out of stock)</span>
                                    <?php elseif ($variants['new']['quantity_in_stock'] == 1): ?>
                                        <span class="text-gray-800">(Only one left in stock)</span>
                                    <?php else: ?>
                                        <span class="text-gray-800"> (<?php echo $variants['new']['quantity_in_stock']; ?> in stock)</span>
                                    <?php endif; ?>
                                </label>
                            <?php endif; ?>

                            <!-- Used variant -->
                            <?php if (isset($variants['used'])): ?>
                                <input type="radio" class="radio radio-primary" name="condition" value="<?php echo $variants['used']['product_variant_id']; ?>" id="used" class="mr-1" <?php echo $variants['used']['quantity_in_stock'] > 0 ? '' : 'disabled'; ?>>
                                <label for="used">Used: <?php echo $variants['used']['price']; ?>
                                    <?php if ($variants['used']['quantity_in_stock'] == 0): ?>
                                        <span class="text-red-500">(Out of stock)</span>
                                    <?php elseif ($variants['used']['quantity_in_stock'] == 1): ?>
                                        <span class="text-yellow-500">(Only one left in stock)</span>
                                    <?php else: ?>
                                        <span class="text-green-500">(<?php echo $variants['used']['quantity_in_stock']; ?> in stock)</span>
                                    <?php endif; ?>
                                </label>
                            <?php endif; ?>
                            <!-- Add to Cart button -->
                            <button type="submit" class="ml-4 px-4 py-2 bg-accent btn-lg text-white rounded transform transition duration-500 ease-in-out hover:bg-orange-800 hover:scale-110">Add to Cart</button>
                            
                    </div>
                </div>
           
             </div><!--End Product Card -->
             <?php
                // Includes the recommendation section
                include './partials/recommendations.php'; 
                ?>     
  </div><!--End Container -->     
   
 
    <!-- Repeat the above structure for each product -->
    <script>
    // Gets the variant IDs from the PHP variables
     var newVariantId = "<?php echo isset($variants['new']) ? $variants['new']['product_variant_id'] : ''; ?>";
    var usedVariantId = "<?php echo isset($variants['used']) ? $variants['used']['product_variant_id'] : ''; ?>";
   //Add to Cart form submission  
    document.querySelector('.add-to-cart-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Getting the value of the checked radio button (the selected condition)
    let selectedCondition = document.querySelector('input[name="condition"]:checked');

    if (selectedCondition) {
        let varId = selectedCondition.value;

        fetch('/cdharmony/cart/add/' + varId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                condition: selectedCondition.id,
                newVariantId: newVariantId, 
                usedVariantId: usedVariantId 
             }), 
        })
        .then(response => response.json())
        .then(data => {
            // Creates a new element for the success message
            let successMessage = document.createElement('div');
            successMessage.textContent = 'Item added to cart successfully!';
            successMessage.classList.add('success-message');

            // Add the success message to the DOM
            document.body.appendChild(successMessage);

            // Update the cart in the UI
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    } else {
        console.error('No condition selected');
    }
});

</script>
<?php
    include_once 'footer.php';
?>



