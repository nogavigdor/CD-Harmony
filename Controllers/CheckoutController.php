<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;
use Services\EmailService;

class CartController {
    public function __construct() {
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }

    public function checkOut() {
       
        // Check if the cart is empty
        if (!SessionManager::isVar('cart')) {
            // Cart is empty, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'You Shopping cart is empty. Please shop before you checkout.');
            header('Location:'. BASE_URL);
            exit();
        }
        // Get the cart items
        $cart = SessionManager::getSessionVariable('cart');
        // Get the user ID
        
        // Create the order
        if(SessionManager::isCustomer()){
           
            $userId = SessionManager::getLoggedInUserId();
            $cart=SessionManager::getSessionVariable('cart');
            $cart['user_id']=$userId;
            SessionManager::setSessionManager('cart', $cart);
        }
        else{
            $userId = null;
            $cart=SessionManager::getSessionVariable('cart');
            $cart['user_id']=$userId;
            SessionManager::setSessionManager('cart', $cart);
        }

        include_once 'views/checkout.php';
        


        
    }


}