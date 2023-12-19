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
        foreach ($cart as $productVarId => $cartItem) {
            // Replace the following with actual product information retrieval logic
            $productName = $cartItem['title']; // Replace with actual product name
            $productImage = 'product-image.jpg'; // Replace with actual product image
            $stock=$cartItem['quantity_in_stock'];

            // Output each product in the cart
            ?>
            <div class="flex items-center border-b border-gray-300 py-2">
                <img src="<?= $productImage ?>" alt="<?= $productName ?>" class="w-16 h-16 object-cover mr-4">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold"><?= $productName ?></h2>
                    <p class="text-gray-600" >Quantity: <input type="number" min="1" max="<?= $stock?>" id="qty__<?= $productVarId ?>" name="qty__<?= $productVarId ?>" value="<?= $cartItem['quantity'] ?>" /><span id="err__<?= $productVarId ?>"></span></p>
                    <p class="text-gray-600">Stock: <?= $stock?></p>
                    <input type="hidden" value="<?= $stock?>" id="stk__<?= $productVarId ?>" name="stk__<?= $productVarId ?>" />
                    <p class="text-gray-600">Price: $<?= number_format($cartItem['price'], 2) ?></p>
                    <?php if ($cartItem['discount'] > 0): ?>
                        <p class="text-gray-600">Discount: <?= $cartItem['discount'] ?>%</p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center">
                    <!-- Add your update and delete logic here -->
                    <button class="bg-blue-500 text-white px-2 py-1 mr-2" id="upd__<?= $productVarId ?>" onclick="updateProductQty(this.id);">Update</button>
                    <button class="bg-red-500 text-white px-2 py-1">Delete</button>
                </div>
            </div>
            <?php
        }
    } else {
        // If the cart is empty
        echo "<p>Your cart is empty.</p>";
    }
    ?>
    <button id="step-cart" class="btn"></button>
    </main>
    <script>
        const baseURL = window.location.origin; // Gets the origin (e.g., http://localhost)
        const relativePath = '/cdharmony/cart/add';
        function updateProductQty(id){
            //alert(id);
            tmp_arr=Array();
            tmp_arr=id.split("__");

            varientId=tmp_arr[1];

            qty=parseInt(document.getElementById("qty__"+varientId).value);
            stock=parseInt(document.getElementById("stk__"+varientId).value);
            //alert(qty+"  "+stock);
            
            if(qty<1 || qty>stock){
                document.getElementById("err__"+varientId).innerHTML="Invalid Quantity";
                return false;
            }else{
                document.getElementById("err__"+varientId).innerHTML="";
            }


            //actual call to the controller starts from here to update the quantity
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
            // document.getElementById("demo").innerHTML =
                alert(this.responseText);

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
    </script>


<?php include 'footer.php'; ?>