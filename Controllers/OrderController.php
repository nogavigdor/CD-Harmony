<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;
use Services\EmailService;

class OrderController {

    private $orderModel;
    private $session;

    public function __construct() {
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }
    public function viewOrderConfirmation()
    {
    try {
        
        // Check if the user is logged in as a customer before checkout
        //If not logged in as a customer the user will be redirected to the login page
        if (!(SessionManager::isLoggedIn()&& SessionManager::isCustomer())) { 
            // User is not logged in, redirect to the login page
            SessionManager::setSessionVariable('error_message', 'Please login in order to checkout.');
            header('Location:'. BASE_URL.   '/login');
            exit;
        }
        //only if logged in as customer the user will be able to place and order
        //and reach the order confirmation page

        //Retrieve cart session variable and insert it into the database
        //and retrieve the order id
        if (SessionManager::isVar('cart') && count(SessionManager::getSessionVariable('cart')) > 0) {
            $cart = SessionManager::getSessionVariable('cart');
            $userId = SessionManager::getSessionVariable('user')['usr_id'];

            // Filter out the non-product items from the cart
            $productItems = array_filter($cart, function($key) {
                return is_numeric($key);
            }, ARRAY_FILTER_USE_KEY);

           
           
            // Insert into orders - sending to model for transaction to be handled
            $orderId = $this->orderModel->createOrder($userId, $productItems);
           
            //if order is placed successfully
             // Empty the cart
             SessionManager::unsetSessionVariable('cart');

             SessionManager::setSessionVariable('success_message', 'Your order has been placed successfully.');
        
             include 'Views/order-confirmation.php';}
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getAllOrders()
    {
        try {
            $orders = $this->orderModel->getAllOrders();
            include 'Views/admin-orders.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }

    
    }
}