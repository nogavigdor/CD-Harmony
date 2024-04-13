    <?php use Services\SessionManager;
    use Controllers\CartController;
    use Controllers\ProductController;

    ?>
    <?php include 'header.php'; ?>

    <!-- Main content section -->
    <main class="container mx-auto p-4 bg-white mt-8 min-h-screen">
        <h1 class="text-2xl headline text-secondary font-bold mb-4">Your Cart</h1>
      
        <?php 
        // Iterate through cart items and display each one
        if (SessionManager::isVar('cart')) { ?>
            
            <div class="flex mb-4">
            <a href="<?= BASE_URL ?>" class="btn flex-1 text-center py-2 m-2">Back to shop</a>  
                <a href="<?= BASE_URL.'/cart/checkout-stripe' ?>" class="btn bg-accent  flex-1 text-center py-2 m-2 ">Checkout</a>
            </div>

            <?php
            $discountTotalVar = 0;
            $subTotalVar = 0;
            $grandTotalVar =0;
            $discountTotal =0; 
            $subTotal = 0;
            $grandTotal = 0;
          
        //looping through the cart session to get the quantity of each product
        foreach ($cart as $product_var_id => $cartItem) {
            if (is_array($cartItem) && isset($cartItem['quantity'])) {
                $product_title = $cartItem['product_title'];
                $artist_title = $cartItem['artist_title'];
                $condition = $cartItem['condition'];
                $image = $cartItem['image'];
                
                $quantity = $cartItem['quantity'];
                
                $price = $cartItem['price'];
                $quantity_in_stock = $cartItem['quantity_in_stock'];
                $discount = $cartItem['discount'];
                //updates the quantity of the product in the cart
                $quantity=$cartItem['quantity'];




                //calculating the total sub total of a specific product (variant) before discount
                $subTotalVar = $price * $quantity; 
            
                //calculating the total discount of a specific product (variant)
                $discountTotalVar = $discount * $quantity;
            //calculating the total grand total of a specific product (variant) - after discount
                $grandTotalVar = $subTotalVar - $discountTotalVar;
                //calculating the total discount of all variants
                $discountTotal+=$discountTotalVar;
                //calculating the total sub total of all products (variants) before discount
                $subTotal+=$subTotalVar;
                //calculating the total grand total of all products (variants) after discount
                $grandTotal+=$grandTotalVar;  
        
            
                $cartItem['discount_total']=$discountTotalVar;
                $cartItem['sub_total']=$subTotalVar;
                $cartItem['grand_total']=$grandTotalVar;

                // Updates the $cart array with the modified $cartItem
                $cart[$product_var_id] = $cartItem;
            
            
            

           

    
                ?>
    <div class="grid grid-cols-4 gap-4 items-center border-b border-gray-300 py-2">
    <img src="<?= BASE_URL.IMAGE_PATH.$cartItem['image']; ?>" alt="<?= $cartItem['product_title'] ?>" class="w-16 h-16 object-cover mr-4">
    <div class="grid col-span-2  grid-cols-2 gap-4">
        <p class="text-lg font-semibold"><?= $cartItem['product_title']?></p>
        <p class="text-lg font-semibold"><?= $cartItem['condition']?></p>
        <p class="text-lg font-semibold"><?= $cartItem['artist_title']?></p>
        <p class="text-gray-600">Quantity: <input class="input" type="number" min="1" max="<?= $cartItem['quantity_in_stock']?>" id="qty__<?= $product_var_id ?>" name="qty__<?= $product_var_id ?>" value="<?= $quantity ?>" />
        <span class="text-red-700" id="err__<?= $product_var_id ?>"></span></p>
        <p class="text-gray-600">Stock: <?= $cartItem['quantity_in_stock']?></p>
        <div class="flex">
            <input type="hidden" value="<?= $cartItem['quantity_in_stock']?>" id="stk__<?= $product_var_id ?>" name="stk__<?= $product_var_id ?>" />
            <span id="err__<?= $product_var_id ?>"></span>
        </div>

        <p class="text-gray-600">Price: <?= number_format($cartItem['price'], 2) ?> DKK</p>
        <?php if ($cartItem['discount'] > 0): ?>
        <p class="text-gray-600">Discount: <?= $cartItem['discount'] ?></p>
        <?php endif; ?>
    </div>
    <div class="flex flex-col gap-y-4">
        <!-- Add your update and delete logic here -->
        <button class="btn bg-secondary w-1/2 text-white px-2 py-1 mr-2" id="upd__<?= $product_var_id ?>" onclick="updateProductQty(this.id);">Update</button>
        <button class="btn bg-red-500 w-1/2 text-white px-2 py-1 " id="dlt__<?= $product_var_id ?>" onclick="deleteProducVar(this.id);" >Delete</button>
    </div>
    </div>
                <?php           
            }
        
            }
            //calculating the totals of all variants
            
            $cart['total_discount']=$discountTotal;
            $cart['total_sub']=$subTotal;
            $cart['total_grand']=$grandTotal;

            if ($cart['total_grand']>0) {
                //Updating the cart session with the new values
                SessionManager::setSessionVariable('cart',$cart);
                    ?>
                <div class="flex flex-col  gap-y-4  md:flex-row gap-x-8 justify-between">
                    <p class="text-lg font-semibold">Total Discount: <?= number_format($cart['total_discount'] ?? 0, 2); ?> DKK</p>
                    <p class="text-lg font-semibold">Total Sub: <?= number_format($cart['total_sub'] ?? 0, 2); ?> DKK</p>
                    <p class="text-lg font-semibold">Total Grand: <?= number_format($cart['total_grand'] ?? 0, 2); ?> DKK</p>
                 </div>
            <div class="flex flex-end"> 
                    <a class="btn bg-accent" href="<?= BASE_URL.'/cart/checkout-stripe';?>" id="checkout" class="btn">Checkout</a>
            </div>


                <?php
            }else{
                SessionManager::unsetSessionVariable('cart');
                 // If the cart is empty
            echo "<p>Your cart is empty.</p>";
            }
     
            ?>

       
            <?php
        } else { ?>
            <a href="<?= BASE_URL ?>" class="btn flex-1 text-center py-2 m-2">Back to shop</a>  
            <?php
            // If the cart is empty
            echo '<h2 class="text-2xl text-secondary headline"> Your cart is empty.</h2>';

              
        } 
        ?>
       
     
       
  
    
        </main>
        <script>
            const baseURL = window.location.origin; 

              // Creates a new div for for alers/sucess message
              let messageDiv = document.createElement('div');
            messageDiv.classList.add('fixed', 'top-20', 'left-1/2', 'transform', '-translate-x-1/2', 'm-6', 'p-4', 'rounded', 'shadow-lg', 'transition', 'duration-500', 'ease-in-out', 'transform', 'z-50');
            messageDiv.style.backgroundColor = '#f3f4f6';
           
            function updateProductQty(id){
                //alert(id);
                tmp_arr=Array();
                tmp_arr=id.split("__");

                varientId=tmp_arr[1];
                //targeting the quantity field value
                qty=parseInt(document.getElementById("qty__"+varientId).value);
                //tareting the stock field value
                stock=parseInt(document.getElementById("stk__"+varientId).value);
                //alert(qty+"  "+stock);
                //preveing the user from updating the quantity if the quantity is less than 1 or greater than the stock
                if(qty<1 || qty>stock){
                    document.getElementById("err__"+varientId).innerHTML="Invalid Quantity";
                    return false;
                }else{
                    document.getElementById("err__"+varientId).innerHTML="";
                }


                //Actual call to the controller starts from here to update the quantity
                
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
            
                // alert(this.responseText);

                    arr_response=Array();
                    arr_response=this.responseText.split("__##__");




                    if(arr_response[0]=="id_missing"){
                        window.location.reload();
                    }
                    else if(arr_response[0]=="success"){
                        var qty=arr_response[1];
                        document.getElementById("cartItemCount").innerHTML=qty;
                            
                        // Sets the a success message upon updating the item quantity
                      messageDiv.textContent = 'Quantaty was updated successfuly.';
                      messageDiv.classList.add('bg-green-800', 'text-gray-900');
                        
                        document.body.appendChild(messageDiv);

                        // Remove the message div after 1 second
                        setTimeout(function() {
                            messageDiv.remove();
                        }, 1000);

                        
                    }

                }
                xhttp.open("GET", baseURL+relativePath+"cart/update-cart/qty/"+qty+"/id/"+varientId);
                xhttp.send();
                
                return false;



            }
        function deleteProducVar(id){

            var confirmNow=confirm("Are you sure you want to delete this product from your cart?");

            if(!confirmNow){
                return false;
            }



            tmp_arr=Array();
            tmp_arr=id.split("__");

            varientId=tmp_arr[1];
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                //alert(this.responseText);
                if (this.responseText == "success") {
                    // Remove the product from the cart in the UI
                    window.location.reload();
                }
            }
            xhttp.open("GET", baseURL+relativePath+"cart/delete-from-cart/id/"+varientId);
            xhttp.send();
        }
        </script>


    <?php include 'footer.php'; ?>