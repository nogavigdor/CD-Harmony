<?php
use Controllers\ProductController;
    include_once 'header.php';
?>

                            
                    <?php
                        
                        $controller = new ProductController();
                        $productVars = $controller->getProductVariantsDetails($product->product_id);
                    
                        // Creates an array of variants, keyed by the condition
                        $variants = [];
                        // Loops through the variants and add them to the array
                        foreach ($productVars as $productVar) {
                            $variants[$productVar['condition_title']] = $productVar;
                        }
                            ?>

<div class="container min-h-screen mx-auto px-4 sm:px-6 lg:px-8 sm:mb-16 sm:mt-16"  >
    <!-- Product Card -->
             <div class="relative bg-white shadow-lg p-4 flex flex-col   ">
           <?php
             ?>
                    <?php
                 
                    if (isset($variants['new'])){
                       if($variants['new']['discount']>0){
                        ?>   
                     <div class="badge w-72 h-16 absolute top-0 left-0 transform -translate-y-4 -translate-x-4 flex bg-base-100 border-secondary border-4 rounded-lg">
                      <span class="p-4 font-bold text-xl text-secondary">
                        <?php
                        echo 'Special offer for new version!';
                        ?>
                        </span>
                    </div>
                        <?php
                    }
                }

                    if (isset($variants['used'])) { 
                      if ($variants['used']['discount']>0){   ?>   
                     <div class="badge w-72 h-16 absolute top-0 left-0 transform -translate-y-4 -translate-x-4 flex bg-base-100 border-secondary border-4 rounded-lg">
                      <span class="p-4 font-bold text-xl text-secondary">
                    <?php
                        echo 'Special offer for used version!';
                     ?>
                     </span>
                    </div>
                    <?php
                      }
                    }
                    ?>
            
                <div class="flex flex-col md:flex-row ">
                    <div class="mr-4 md:w-1/3   ">
                    <img  class="w-full rounded-md object-contain md:object-cover"  src="<?= BASE_URL ?>/src/assets/images/albums/<?php echo htmlspecialchars($product->image_name) ?>" alt="Product Image">
                    </div>
                    <div class="flex flex-col  mt-4 md:flex-row">

            
                            <form class="add-to-cart-form flex flex-col align-center ">
                     
                            <div class="flex flex-col  "> 
                                <!-- Product condition selection -->
                                <div class="flex flex-row justify-between gap-x-8 w-full" >
                                    <!-- New variant -->
                                      <div class="flex gap-x-4 ">
                                            <?php if (isset($variants['new'])): ?>
                                                <input type="radio" checked class="radio radio-primary" name="condition" value="<?php echo $variants['new']['product_variant_id']; ?>" id="new" class="mr-1" <?php echo $variants['new']['quantity_in_stock'] > 0 ? '' : 'disabled'; ?>>
                                                <label for="new">New: <?php echo $variants['new']['price']-$variants['new']['discount'].' DKK'; ?>
                                                    <?php if ($variants['new']['quantity_in_stock'] == 0): ?>
                                                        <span class="text-gray-800">(Out of stock)</span>
                                                    <?php elseif ($variants['new']['quantity_in_stock'] == 1): ?>
                                                        <span class="text-gray-800">(Only one left in stock)</span>
                                                    <?php else: ?>
                                                        <span class="text-gray-800"> (<?php echo $variants['new']['quantity_in_stock']; ?> in stock)</span>
                                                    <?php endif; ?>
                                                </label>
                                            <?php endif; ?>
                                        </div>  
                                        <div class="flex gap-x-4 ">             
                                        <!-- Used variant -->
                                            <?php if (isset($variants['used'])): ?>
                                                <input type="radio" class="radio radio-primary" name="condition" value="<?= $variants['used']['product_variant_id']; ?>" id="used" class="mr-1" <?php echo $variants['used']['quantity_in_stock'] > 0 ? '' : 'disabled'; ?>>
                                                <label for="used">Used: <?= $variants['used']['price']-$variants['used']['discount'].' DKK'; ?>
                                                    <?php if ($variants['used']['quantity_in_stock'] == 0): ?>
                                                        <span class="text-gray-800">(Out of stock)</span>
                                                    <?php elseif ($variants['used']['quantity_in_stock'] == 1): ?>
                                                        <span class="text-gray-800">(Only one left in stock)</span>
                                                    <?php else: ?>
                                                        <span class="text-gray-800">(<?= $variants['used']['quantity_in_stock']; ?> in stock)</span>
                                                    <?php endif; ?>
                                                </label>
                                                        <?php endif; ?>
                                        </div>   
                                    </div>
                                        <!-- Add to Cart button -->
                                        
                                        <?php
                                        if(isset($variants['new']) || isset($variants['used'])){?>
                                        <button type="submit" class="w-52 mt-16 ml-4 px-4 -32 py-2 bg-accent btn-lg text-white rounded transform transition duration-500 ease-in-out hover:brightness-125 md:w-2/3 ">Add to Cart</button>
                                        <?php
                                        }
                                        else{
                                            ?>
                                            <p class="text-xl text-primary mt-2">We appologize but the product is currently unavailble.<p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    </form>    

                    </div>
             
                    </div>
                      <div class="flex flex-col mt-8 md:w-1/2">      
                            <p class="headline text-primary text-2xl mt-2"><?= htmlspecialchars($product->product_title) ?></p>
                            <p class="text-xl text-primary mt-2    "><?= htmlspecialchars($product->artist_title) ?></p>
                            <p class="mt-2"><?= htmlspecialchars($product->product_description) ?></p>
                        
                      </div> 
                </div>
                <?php
                // Includes the recommendation section
                include './partials/recommendations.php'; 
                ?>     
  </div><!--End Container -->     
             </div><!--End Product Card -->
            
   
 
    <!-- Repeat the above structure for each product -->
    <script>
// Gets the variant IDs from the PHP variables
let newVariantId = "<?= isset($variants['new']) ? $variants['new']['product_variant_id'] : ''; ?>";
let usedVariantId = "<?= isset($variants['used']) ? $variants['used']['product_variant_id'] : ''; ?>";

//Add to Cart form submission  
document.querySelector('.add-to-cart-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Getting the value of the checked radio button (the selected condition)
    let selectedVariantId = document.querySelector('input[name="condition"]:checked').value;
/*
    let selectedVariantId;
    if (selectedCondition === 'new') {
        selectedVariantId = newVariantId;
    } else if (selectedCondition === 'used') {
        selectedVariantId = usedVariantId;
    }
*/

const baseURL = window.location.origin;     



    if (selectedVariantId) {

        //alert(baseURL+relativePath);


        
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
           // document.getElementById("demo").innerHTML =
           // alert(this.responseText);

            arr_response=Array();
            console.log("Request URL:", this.responseURL);
            console.log(this.responseText);
                arr_response=this.responseText.split("|");
                console.log(arr_response);

            // Creates a new div for for alers/sucess message
            let messageDiv = document.createElement('div');
            messageDiv.classList.add('fixed', 'top-20', 'left-1/2', 'transform', '-translate-x-1/2', 'm-6', 'p-4', 'rounded', 'shadow-lg', 'transition', 'duration-500', 'ease-in-out', 'transform', 'z-50');



            if(arr_response[0]=="id_missing"){
                window.location.reload();
            }
            else if(arr_response[0]=="success"){
                let qty=arr_response[1];
                document.getElementById("cartItemCount").innerHTML=qty;

                // Sets the success upon adding an item to the cart
                messageDiv.textContent = 'Item added';
                messageDiv.classList.add('bg-green-800', 'text-white');
            }
            else {
                // Sets the error message upon adding an item to the cart
                messageDiv.textContent = 'Out of stock.';
                messageDiv.classList.add('bg-red-800', 'text-white');
            }
              // Adds the message alers/success message div to the DOM
                document.body.appendChild(messageDiv);

                // Remove the message div after 3 seconds
                setTimeout(function() {
                    messageDiv.remove();
                }, 1000);

        }
        xhttp.open("GET", baseURL+relativePath+"cart/id/"+selectedVariantId);
        xhttp.send();
        
        return false;






        /*fetch(`${baseURL}${relativePath}`,  {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                product_variant_id: selectedVariantId,
                quantity: 1  
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
            });*/
    } else {
        console.error('No condition selected');
    }
});
</script>
<?php
    include_once 'footer.php';
?>



