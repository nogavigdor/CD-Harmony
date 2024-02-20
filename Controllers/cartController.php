<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;

class CartController {
    private $productModel;
    private $session;
    private $orderModel;
    public function __construct() {
        $this->productModel = new ProductModel();
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }

    // Add a product variant to the cart
    //Controlls and update the cart through the Add to cart button and update button at the cart page
    public function addToCart($product_variant_id) {
        $quantity=1;
        
        // Check if the product variant ID and quantity are provided
        if (!isset($product_variant_id)) {
            // Product variant ID is not provided, redirect to the home page

            echo "id_missing";
            exit;

        }
        $productVar = $this->productModel->getProductVariantDetails($product_variant_id);
        
        $productVarStock = $productVar['quantity_in_stock'];


        //checks if cart exits
        if (SessionManager::isVar('cart')) {
            $cart = SessionManager::getSessionVariable('cart');


          

            if(isset($cart[$product_variant_id])){
                if(($cart[$product_variant_id]['quantity']+$quantity)>$productVarStock){
                    echo "quantity_exceeds";
                    exit;
                }
            }
            // Check if the product variant is already in the cart
            if(isset($cart[$product_variant_id])) {
                // Product variant is already in the cart, increase the quantity
               //this check is for the product details page only. as if some one clicks the add to cart again and again the quantity will increase
                
                    $cart[$product_variant_id]['quantity'] += $quantity;
                
            } else {
                // Product variant is not in the cart, add it
                $cart[$product_variant_id] = [
                    
                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => $quantity,
                    'price' => $productVar['price'],
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVar['discount'],
                ];


               
            }
        } else {
            // Cart is empty, adds the product variant
            $cart = [
                $product_variant_id => [

                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => $quantity,
                    'price' => $productVar['price'],
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVar['discount'],
             
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
            if(isset($cartItem['quantity'])){
                $total_qty+=$cartItem['quantity'];
            }
        }
    
        echo "success__##__".$total_qty;

        
    }

    public function updateCart($quantity, $product_variant_id) {
       
       
        // Check if the product variant ID and quantity are provided
        if (!isset($product_variant_id) || !isset($quantity)) {
            // Product variant ID or quantity are not provided, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'There was an error while adding the product to the cart. Please try again.');
            echo "id_missing";
            exit;

        }
        $productVar = $this->productModel->getProductVariantDetails($product_variant_id);
        
        
        $productVarPrice =$productVar['price'];
 
        $productVarDiscount = $productVar['discount']??0;

        $productVarStock = $productVar['quantity_in_stock'];
       
        


        //checks if cart exits
        if (SessionManager::isVar('cart')) {
            $cart = SessionManager::getSessionVariable('cart');


          

            if(isset($cart[$product_variant_id])){
                if($quantity>$productVarStock){
                    echo "quantity_exceeds";
                    exit;
                }
            }
            // Check if the product variant is already in the cart
            if(isset($cart[$product_variant_id])) {
                // Product variant is already in the cart, increase the quantity
               //this check is for the product details page only. as if some one clicks the add to cart again and again the quantity will increase
                
                    $cart[$product_variant_id]['quantity'] = $quantity;
               
            } else {
                // Product variant is not in the cart, add it
                $cart[$product_variant_id] = [
                    
                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => $quantity,
                    'price' => $productVar['price'],
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVar['discount'],
                ];


               
            }
        } else {
            // Cart is empty, adds the product variant
            $cart = [
                $product_variant_id => [

                    'product_title' => $productVar['product_title'], 
                    'artist_title' => $productVar['artist_title'], 
                    'condition' => $productVar['condition_title'],
                    'image' => $productVar['image_name'],
                    'quantity' => $quantity,
                    'price' => $productVar['price'],
                    'quantity_in_stock' => $productVar['quantity_in_stock'],
                    'discount' => $productVar['discount'],
             
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
            if(isset($cartItem['quantity'])){
                $total_qty+=$cartItem['quantity'];
            }
        }
        
        echo "success__##__".$total_qty;
    }

    public function deleteFromCart($productVarId) {
        
        if (SessionManager::isVar('cart')) {
   ;
            $cart = SessionManager::getSessionVariable('cart');
            if (isset($cart[$productVarId])) {
                unset($cart[$productVarId]);
                SessionManager::setSessionVariable('cart', $cart);
            }

            $total_qty=0;
            foreach ($cart as $productVarId => $cartItem) {
                if(isset($cartItem['quantity'])){
                    $total_qty+=$cartItem['quantity'];
                }
            }
            
            echo "success";
        }
        
    }

    public function emptyCart() {
        SessionManager::unsetSessionVariable('cart');
    }

    public function viewCart() {
        $cart = SessionManager::isVar('cart') ? SessionManager::getSessionVariable('cart') : [];
        include 'views/cart.php';
    }

   

}