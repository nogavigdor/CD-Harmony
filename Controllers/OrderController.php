<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

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

             $customerEmail = SessionManager::getSessionVariable('user')['email'];
             $customerName = SessionManager::getSessionVariable('user')['first_name'];

           
             
             //fetching the order details from the database
            $invoiceDetails = $this->orderModel->getOrderLines($orderId);
            /*    
             $mail = new PHPMailer(true);
                                
             $mail->SMTPDebug = SMTP::DEBUG_SERVER;
             $mail->isSMTP();
             $mail->SMTPAuth = true;
           
             $mail->Host = "send.one.com";
             $mail->SMTPSecure = "ssl";
             $mail->SMTPDebug = 0; 
             $mail->Port = 465;

             $mail->Username = SMTP_USERNAME;
             $mail->Password = SMTP_PASSWORD;   
             //$mail->setFrom($email, $first_name);
             $mail->isHTML();

             $mail->From = "contact@cdharmony.dk";
            $mail->addAddress($invoiceDetails[0]['customer_email'], $invoiceDetails[0]['customer_name']);
            $mail->Subject = 'Order Confirmation';

            $message = 'Thank you for your recent order from CD Harmony';

            $mail->Subject = 'You have placed an order with CD Harmony';
            $mail->Body = "<h2 style='color:red;'>Email From:</h2> ".$invoiceDetails[0]['customer_email']."<br />".$message;
           */  

             /* "
            <h2>Dear {$invoiceDetails['customer_name']},</h2>
            <p>Thank you for your recent order from CD Harmony. We appreciate your business. Here are the details of your order:</p>
            <p><strong>Order ID:</strong> {$invoiceDetails[0]['order_id']}</p>
            <p><strong>Order Date:</strong> {$invoiceDetails[0]['order_date']}</p>
            <p><strong>Product Name:</strong> {$invoiceDetails[0]['product_name']}</p>
            <p><strong>Quantity:</strong> {$invoiceDetails[0]['quantity_per_variant']}</p>
            <p><strong>Unit Price:</strong> {$invoiceDetails[0]['unit_price']}</p>
            <p><strong>Order Subtotal:</strong> {$invoiceDetails[0]['order_subtotal']}</p>
            <p><strong>Order Discount:</strong> {$invoiceDetails[0]['order_discount']}</p>
            <p><strong>Order Grand Total:</strong> {$invoiceDetails[0]['order_grand_total']}</p>
            <p>If you have any questions about your order, please contact us at <a href='mailto:contact@cdharmony.dk'>contact@cdharmony.dk</a>.</p>
            <p>Thank you again for your purchase. We hope to see you again soon at <a href='https://www.cdharmony.dk'>www.cdharmony.dk</a>.</p>
            <p>Best regards,</p>
            <p>The CD Harmony Team</p>
            ";
      
            $mail->send();
            */
             include 'Views/order-confirmation.php';}
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getAllOrders()
{
    try {
        // Retrieve all orders from the database
        return $this->orderModel->getAllOrders();
    } catch (\PDOException $ex) {
        error_log('PDO Exception: ' . $ex->getMessage());
    }
}

public function showOrders()
{
    try {
        // Checks if the user is logged in as an admin before showing the orders
        if (!SessionManager::isAdmin()) {
            // User is not logged in as an admin, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
            header('Location:'. BASE_URL.   '/login');
            exit;
        }

        // Retrieve all orders from the database
        $orders = $this->getAllOrders();

        include 'Views/admin/orders.php';
    } catch (\PDOException $ex) {
        error_log('PDO Exception: ' . $ex->getMessage());
    }
}


public function showInvoice($orderId)
{
    try {
        // Checks if the user is logged in as an admin before showing the invoice
        if (!SessionManager::isAdmin()) {
            // User is not logged in as an admin, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
            header('Location:'. BASE_URL.   '/login');
            exit;
        }

        // Retrieve the order details from the database
        $invoiceDetails = $this->orderModel->getOrderLines($orderId);

        include 'Views/admin/invoice.php';
    } catch (\PDOException $ex) {
        error_log('PDO Exception: ' . $ex->getMessage());
    }
  
}
}