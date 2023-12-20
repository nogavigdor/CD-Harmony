<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;

class CartController {
    public function __construct() {
        $this->productModel = new ProductModel();
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }

    // Add a product variant to the cart
    //Controlls and update the cart through the Add to cart button and update button at the cart page
    public function addToCart($quantity, $product_variant_id) {

        
        // Check if the product variant ID and quantity are provided
        if (!isset($product_variant_id) || !isset($quantity)) {
            // Product variant ID or quantity are not provided, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'There was an error while adding the product to the cart. Please try again.');
            echo "id_missing";
            exit;

        }
        $productVar = $this->productModel->getProductVariantDetails($product_variant_id);
        
        

        $productVarPrice = $productVar['price'];
        
        $productVarDiscount = $productVar['discount'];

        $productVarStock = $productVar['quantity_in_stock'];
       
        


        //checks if cart exits
        if (SessionManager::isVar('cart')) {
            $cart = SessionManager::getSessionVariable('cart');


           

            if(isset($cart[$product_variant_id])){
                //from the details every time you will only receive 1 quantity at a time so that quantity will get added with the existing cart item to decide the4 stock
                if(($cart[$product_variant_id]['quantity']+$quantity)>=$productVarStock && $quantity==0){
                    echo "quantity_exceeds";
                    exit;
                } //in this case from the cart page you will get the exact quantity you want so it will directly check that quantity only
                else if($quantity>=$productVarStock){
                    echo "quantity_exceeds";
                    exit;
                }
            }
            // Check if the product variant is already in the cart
            if(isset($cart[$product_variant_id])) {
                // Product variant is already in the cart, increase the quantity
                
                if($quantity==0){
                    $cart[$product_variant_id]['quantity'] += 1;
                }
                else{
                    $cart[$product_variant_id]['quantity'] = $quantity;
                }
            } else {
                // Product variant is not in the cart, add it
                $cart[$product_variant_id] = [
                    'product_variant_id' => $product_variant_id,
                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => 1,
                    'price' => $productVarPrice,
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVarDiscount
                ];


               
            }
        } else {
            // Cart is empty, adds the product variant
            $cart = [
                $product_variant_id => [
                    'product_variant_id' => $product_variant_id,
                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => 1,
                    'price' => $productVarPrice,
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVarDiscount
                ]
            ];
        }
        // Save the cart in the session
        SessionManager::setSessionVariable('cart', $cart);
        //header('Location:'. BASE_URL. '/cart');
        //exit();
        //here please calsulate the total quantity of product that you have in session
        $cart = SessionManager::getSessionVariable('cart');
        $total_qty=0;
        foreach ($cart as $productVarId => $cartItem) {
            $total_qty+=$cartItem['quantity'];
        }
        
        echo "success__##__".$total_qty;
    }

    public function removeFromCart($productVarId) {
        if (SessionManager::isVar('cart')) {
            $cart = SessionManager::getSessionVariable('cart');
            if (isset($cart[$productVarId])) {
                unset($cart[$productVarId]);
                SessionManager::setSessionVariable('cart', $cart);
            }
        }
        header('Location:'. BASE_URL. '/cart');
        exit();
    }

    public function emptyCart() {
        SessionManager::unsetSessionVariable('cart');
    }

    public function viewCart() {
        $cart = SessionManager::isVar('cart') ? SessionManager::getSessionVariable('cart') : [];
        include 'views/cart.php';
    }

    public function checkout() {

    // Check if the user is logged and  a customer before checkout
    if (!SessionManager::isVar('user_id')&& SessionManager::isCustomer()) {
        // User is not logged in, redirect to the login page
        SessionManager::setSessionVariable('error_message', 'Please login in order to checkout.');
        header('Location: /login');
        exit;
    }
    //user is logged in as a customer and therefore checkout is allowed
    // Check if the cart is not empty and if there is at least one product variant in the cart
        if (SessionManager::isVar('cart') && count(SessionManager::getSessionVariable('cart')) > 0) {
            $cart = SessionManager::getSessionVariable('cart');
            $userId = SessionManager::getSessionVariable('user_id');
    
          
                $orderPlaced = $this->orderModel->createOrder($userId, $cart);
    
                if ($orderPlaced) {
                    // Order placed successfully
                     // Empty the cart
                    SessionManager::unsetSessionVariable('cart');
                    SessionManager::setSessionVariable('success_message', 'Your order has been placed successfully.');
                    header("Location: " . BASE_URL);
                    exit;
                } else {
                    // An error occurred while placing the order
                    SessionManager::setSessionVariable('error_message', 'An error occurred while placing your order. Please try again.');
                    header("Location: " . BASE_URL . "/cart");
                    exit;
                }
          
        }
    }

}