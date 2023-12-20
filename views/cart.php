<?php use Services\SessionManager;
use Controllers\CartController;
use Controllers\ProductController;

?>
<?php include 'header.php'; ?>

<!-- Main content section -->
<main class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Your Cart</h1>
    <div class="flex mb-4">
        <div class="flex-1 text-center py-2 m-2 bg-blue-500 text-white">Cart</div>
        <div class="flex-1 text-center py-2 m-2 bg-gray-300">Login</div>
        <div class="flex-1 text-center py-2 m-2 bg-gray-300">Checkout</div>
    </div>
    <?php
    // Iterate through cart items and display each one
    if (SessionManager::isVar('cart')) {
        $cart = SessionManager::getSessionVariable('cart');
        $sub_total=0;   
        $discount_total=0;
        $grand_total=0;

    //to keet the cart details accuracy, I'll only be using the quantiy from the cart session
    //and the other data I'll pull from the database and not from the cart session
    //in order to have the most accurrate data
    foreach ($cart as $product_var_id => $cartItem) {
        $quantity=$cartItem['quantity'];
       
            ?>
            <div class="flex items-center border-b border-gray-300 py-2">
        
                <img src="<?= BASE_URL.IMAGE_PATH.$cartItem['image']; ?>" alt="<?= $cartItem['product_title'] ?>" class="w-16 h-16 object-cover mr-4">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold"><?= $cartItem['product_title']?></h2>
                    <p class="text-gray-600" >Quantity: <input type="number" min="1" max="<?= $cartItem['quantity_in_stock']?>" id="qty__<?= $product_var_id ?>" name="qty__<?= $product_var_id ?>" value="<?= $quantity ?>" /><span id="err__<?= $product_var_id ?>"></span></p>
                    <p class="text-gray-600">Stock: <?= $cartItem['quantity_in_stock']?></p>
                    <input type="hidden" value="<?= $cartItem['quantity_in_stock']?>" id="stk__<?= $product_var_id ?>" name="stk__<?= $product_var_id ?>" />
                    <p class="text-gray-600">Price: $<?= number_format($cartItem['price'], 2) ?></p>
                    <?php if ($cartItem['discount'] > 0): ?>
                        <p class="text-gray-600">Discount: <?= $cartItem['discount'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <!-- Add your update and delete logic here -->
                    <button class="bg-blue-500 text-white px-2 py-1 mr-2" id="upd__<?= $product_var_id ?>" onclick="updateProductQty(this.id);">Update</button>
                    <button class="bg-red-500 text-white px-2 py-1" id="dlt__<?= $product_var_id ?>" onclick="deleteProducVar(this.id);" >Delete</button>
                </div>
            </div>
            <?php
        }
    } else {
        // If the cart is empty
        echo "<p>Your cart is empty.</p>";
    }
    ?>
    <button id="checkout" class="btn">Checkout</button>
    </main>
    <script>
        const baseURL = window.location.origin; // Gets the origin (e.g., http://localhost)
        const relativePath = '/cdharmony/update-cart/qty';
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
                }

            }
            xhttp.open("GET", baseURL+relativePath+"/"+qty+"/id/"+varientId);
            xhttp.send();
            
            return false;



        }
/*
        deleteProducVar(id){
            tmp_arr=Array();
            tmp_arr=id.split("__");

            varientId=tmp_arr[1];
            //alert(varientId);
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
           
             //   alert(this.responseText);

                arr_response=Array();
                arr_response=this.responseText.split("__##__");
        }
        */
    </script>


<?php include 'footer.php'; ?>