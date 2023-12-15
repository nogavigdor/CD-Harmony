<?php
    include_once 'header.php';
?>

<div class="container min-h-screen mx-auto px-4 sm:px-6 lg:px-8 sm:mb-16 sm:mt-16"  >
    <!-- Product Card -->
             <div class="bg-white shadow-lg p-4 flex-col  flex md:flex-row mx-auto  ">
                <div class="mr-4 md:w-1/3   ">
                  <img  class="w-full rounded-md object-contain md:object-cover"  src="<?= BASE_URL ?>/src/assets/images/albums/<?php echo htmlspecialchars($product->image_name) ?>" alt="Product Image">
                </div>
            
                <div class="mt-4 md:w-2/3">
                    <h2 class="text-2xl font-semibold mt-2"><?php echo $product->product_title ?></h2>
                    <p class="text-sm text-gray-600"><?php echo $product->artist_title ?></p>
                    <p class="mt-2"><?php echo $product->product_description ?></p>
                    <div class="text-lg font-semibold mb-2">Price:</div>
                    <div>
                        <input type="radio" name="condition" value="new" id="new" class="mr-1">
                        <label for="new">New: <?php echo $product-> price ?></label>
                     </div>
                    <div>
                        <input type="radio" name="condition" value="used" id="used" class="mr-1">
                        <label for="used">used: <?php echo $product->price ?></label>
                    </div>
                     <div class="text-lg font-semibold">Stock Quantities:</div>
                    <div>
                        <button class="px-4 py-2 bg-primary text-white rounded mr-2">-</button>
                        <span>10</span>
                        <button class="px-4 py-2 bg-primary text-white rounded ml-2">+</button>
                    </div>
                </div>
           
             </div><!--End Product Card -->
             <?php
                // Includes the recommendation section
                include './partials/recommendations.php'; 
                ?>     
  </div><!--End Container -->     
   
 
    <!-- Repeat the above structure for each product -->

<?php
    include_once 'footer.php';
?>



