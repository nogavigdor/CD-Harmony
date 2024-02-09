<?php
namespace Controllers;
use Models\CartModel;
use Models\ProductModel;
use Models\UserModel;
use Services\SessionManager;

/* This controller will handle the sessions and the database for the cart
 * The cart will be stored in the session if no user is logged in
 * If a user is logged in, the cart will be stored in the database
 * The cart will be merged with the user cart if the user is logged in
 * The cart will be cleared if the user is logged out
 */

// Shopping Cart controller
class CartController {
    //neccessary models initialized
    public function __construct() {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->session = new SessionManager();
        $this->session->startSession();
    }
    //add items to cart
    public function addToCart($productVarId, $quantity) {
        // Check if a user is logged in as a custommer

        echo "dsfgdfg";
        exit;
        if(SessionManager::isCustomer())
         $userId = SessionManager::getLoggedInUserId();

         try {
            // Check if the product exists in the cart
            if($this->cartModel->checkIfProductExists($productVarId) == false){
                $this->cartModel->addItemToCart($productVarId, $quantity);
            }
        
            $product = $this->productModel->getProductVariantsDetails($productVarId);
            
            $productPrice = $product['price'];
            //A side note: the discount is joint from the special_offers table through a view in the database
            $productDiscount = $product['discount'];
        //    $productDiscountedPrice = $productPrice - $productDiscount;
       //     $productDiscountedPrice = number_format((float)$productDiscountedPrice, 2, '.', '');
       //     $productTotal = $productDiscountedPrice * $quantity;
      //      $productTotal = number_format((float)$productTotal, 2, '.', '');
            $cartMasterId = $this->cartModel->getCartMasterId($userId);
            if ($cartMasterId) {
                // Cart exists
                //
                $cartItems = $this->cartModel->getCartItems($cartMasterId);
                $cartItems = $cartItems[0];
                $cartItemsId = $cartItems['id'];
                $cartItemsProductId = $cartItems['product_id'];
                $cartItemsQuantity = $cartItems['Quantity'];
                $cartItemsPrice = $cartItems['price'];
                $cartItemsDiscount = $cartItems['discount'];
                $cartItemsTotal = $cartItems['total'];
                if ($cartItemsProductId == $productId) {
                    // Product already exists in the cart
                    $newQuantity = $cartItemsQuantity + $quantity;
                    $newTotal = $cartItemsTotal + $productTotal;
                    $this->cartModel->updateCartDetails($cartMasterId, $productId, $newQuantity, $newTotal);
                    $this->cartModel->updateCartMaster($cartMasterId, $newQuantity, $newTotal);
                } else {
                    // Product does not exist in the cart
                    $this->cartModel->createCartDetails($cartMasterId, $productId, $quantity, $productDiscountedPrice, $productDiscount, $productTotal);
                    $this->cartModel->updateCartMaster($cartMasterId, $quantity, $productTotal);
                }
            } else {
                // Cart does not exist
                $cartMasterId = $this->cartModel->createCart($userId);
                $this->cartModel->createCartDetails($cartMasterId, $productId, $quantity, $productDiscountedPrice, $productDiscount, $productTotal);
                $this->cartModel->updateCartMaster($cartMasterId, $quantity, $productTotal);
            }
            // Redirect to the cart page
            header('Location:'. BASE_URL. '/cart');
            exit();
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //remove items from cart
    public function emptyCart() {
        // Check if a user is logged in
        if(SessionManager::isCustomer()) {
            $userId = SessionManager::getLoggedInUserId();
            $cartMasterId = $this->cartModel->getCartMasterId($userId);
            if ($cartMasterId) {
                $this->cartModel->clearCartDetails($cartMasterId);
                $this->cartModel->updateCartMaster($cartMasterId, 0, 0);
            }
        } else {
            // If no user is logged in, handle the cart in the session
            if (SessionManager::isVar('cart')) {
                SessionManager::unsetSessionVariable('cart');
            }
        }
    }
    public function mergeSessionCartWithUserCart() {
        // Check if a user is logged in
        if(SessionManager::isCustomer()) {
            $userId = SessionManager::getLoggedInUserId();
            $cartMasterId = $this->cartModel->getCartMasterId($userId);
            if ($cartMasterId) {
                // If the user has a cart in the database, merge the session cart with the user cart
                if (SessionManager::isVar('cart')) {
                    $cart = SessionManager::getSessionVariable('cart');
                    $cartItems = $cart['cart_master']['cart_items'];
                    foreach ($cartItems as $item) {
                        $this->cartModel->createCartItem($cartMasterId, $item['product_variant_id'], $item['quantity'], $item['price'], $item['discount'], $item['total_price']);
                    }
                    $this->cartModel->updateCartMaster($cartMasterId, $cart['cart_master']['quantity'], $cart['cart_master']['total']);
                    SessionManager::unsetSessionVariable('cart');
                }
            } else {
                // If the user does not have a cart in the database, create a cart for the user
                if (SessionManager::isVar('cart')) {
                    $cart = SessionManager::getSessionVariable('cart');
                    $cartMasterId = $this->cartModel->createCartMaster($userId);
                    $cartItems = $cart['cart_master']['cart_items'];
                    foreach ($cartItems as $item) {
                        $this->cartModel->createCartItems($cartMasterId, $item['product_variant_id'], $item['quantity'], $item['price'], $item['discount'], $item['total_price']);
                    }
                    $this->cartModel->updateCartMaster($cartMasterId, $cart['cart_master']['quantity'], $cart['cart_master']['total']);
                    SessionManager::unsetSessionVariable('cart');
                }
            }
     
    }
}

//in case customer is logged in the cart is retreived from the database
//in case customer is not logged in the cart is retreived from the session
//the cart is then displayed in the cart view right after
public function viewCart() {
    // Check if a user is logged in
    if(SessionManager::isCustomer()) {
        $userId = SessionManager::getLoggedInUserId();
        $cartMasterId = $this->cartModel->getCartMasterId($userId);
        if ($cartMasterId) {
            $cart = $this->cartModel->getCartDetails($cartMasterId);
            $cart = $cart[0];
            $cartItems = $this->cartModel->getCartItems($cartMasterId);
            $cart['cart_items'] = $cartItems;
            $cart['cart_master']['cart_items'] = $cartItems;
            SessionManager::setSessionVariable('cart', $cart);
        }
    } else {
        // If no user is logged in, handle the cart in the session
        if (SessionManager::isVar('cart')) {
            $cart = SessionManager::getSessionVariable('cart');
            // Assuming the cart structure in the session is the same as the one in the database
            $cartItems = $cart['cart_master']['cart_items'];
            $cart['cart_items'] = $cartItems;
        } else {
            // If there's no cart in the session, create an empty one
            $cart = ['cart_items' => [], 'cart_master' => ['cart_items' => []]];
        }
    }
    include 'views/cart.php';
}

    public function checkout() {
        SessionManager::startSession();

        $userId = SessionManager::getLoggedInUserId();

        if ($userId) {
            // If a user is logged in, handle the cart in the database
            // ...
        } else {
            // If no user is logged in, handle the cart in the session
            if (SessionManager::isVar('cart')) {
                // Create an order
                $cart = SessionManager::getSessionVariable('cart');
                $orderMasterId = $this->orderModel->createOrderMaster($cart['cart_master']);

                // Create order details
                $cartItems = $cart['cart_master']['cart_items'];
                foreach ($cartItems as $item) {
                    $this->orderModel->createOrderDetails($orderMasterId, $item);
                }

                // Empty the cart
                SessionManager::unsetSessionVariable('cart');
            }
        }
    }
   
}