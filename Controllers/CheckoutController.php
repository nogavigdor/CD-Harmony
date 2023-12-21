<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;
use Services\EmailService;

class CheckoutController {
    public function __construct() {
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }

    public function checkoutView(){

        try{

            include 'Views/checkout.php';
        }catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }

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