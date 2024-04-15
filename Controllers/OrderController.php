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

            //later on, when stripe webook is implemented, the payment status and order status will be dynamically updated
            //by listening to the stripe webhook events. As for now, the payment status and order status are hardcoded
            //once the order is placed - when stripe redicrect to the order confirmation page upon successful payment
            $order_payment_id = 2; // 2 for 'Paid' (payment status)
            $order_status_id = 3; // 3 for 'Completed' (delivery status)
           
            // Insert into orders - sending to model for transaction to be handled
            $orderId = $this->orderModel->createOrder($userId, $productItems, $order_status_id = 3, $order_payment_id = 2);
            
            $invoiceDetails = $this->orderModel->getInvoiceDetails($orderId);
            
            $to = SessionManager::getSessionVariable('user')['email'];
            $subject = 'Order Confirmation';
            $from = ADMIN_EMAIL;
            $headers = "From: " . ADMIN_EMAIL . "\r\n";
            $message = 
           "<p><strong>Order ID:</strong> {$orderId}</p>
            <p><strong>Order Date:</strong> {$invoiceDetails[0]['order_date']}</p>
            <p>Dear {$invoiceDetails[0]['customer_name']},</p>
            <p>Thank you for your recent order from CD Harmony.</p>
            <p>Here are the details of your order:</p>
            ";
           
            foreach ($invoiceDetails as $productVar => $value) {
            $message .= "
            <p><strong>Product Name:</strong> {$value['product_name']}</p>
            <p><strong>Quantity:</strong> {$value['quantity_per_variant']}</p>
            <p><strong>Unit Price:</strong> {$value['unit_price']}DKK</p>
            <p><strong>Order Subtotal:</strong> {$value['order_subtotal']}DKK</p>
            <p><strong>Order Discount:</strong> {$value['order_discount']}DKK</p>
            <p><strong>Order Grand Total:</strong> {$value['order_grand_total']}DKK</p>
            ";
            }
            $message .= "
            <p>Thank you again for your purchase. We hope to see you again soon at <a href='https://www.cdharmony.dk'>www.cdharmony.dk</a>.</p>
            <p>If you have any questions about your order, please contact us at <a href='mailto:contact@cdharmony.dk'>contact@cdharmony.dk</a>.</p>
            <p>Best regards,</p>
            <p>The CD Harmony Team</p>
            ";
            

                
            sendMail($to, $subject, $message, $from, $headers);
            //if order is placed successfully
             // Empty the cart
             SessionManager::unsetSessionVariable('cart');

             SessionManager::setSessionVariable('success_message', 'Your order has been placed successfully. Please check your email for order confirmatoin');

             $customerEmail = SessionManager::getSessionVariable('user')['email'];
             $customerName = SessionManager::getSessionVariable('user')['first_name'];

             include 'views/order-confirmation.php';}
             
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

        include 'views/admin/orders.php';
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
        //general overview of the order
        $total = $this->orderModel->getOrderSummary($orderId);
        //details of each product variant in the order
        $items = $this->orderModel->getInvoiceDetails($orderId);
       

        
        
        include 'views/admin/invoice.php';

    } catch (\PDOException $ex) {
        error_log('PDO Exception: ' . $ex->getMessage());
    }
  
}

    public function sendInvoice()
    {
        try {
            // Checks if the user is logged in as an admin before showing the invoice
            if (!SessionManager::isAdmin()) {
                // User is not logged in as an admin, redirect to the home page
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:'. BASE_URL.   '/login');
                exit;
            }

            //include 'Views/admin/send-invoice.php';
            if (isset($_POST['invoiceHtml'])) {
                    $orderId = $_POST['orderId'];
                    $customerName = $_POST['customerName'];
                    $message = $_POST['invoiceHtml'];
                    $message = "An invoice from {$customerName},<br><br>";
                    $message .= $_POST['invoiceHtml'];
                    $message .= "<br><br>This was sent from the admin.<br>";
                    $subject = "Invoice Details - Order No. $orderId";
                    $from = ADMIN_EMAIL;
                    $to = $_POST['to'];
                    $headers = "From: " . ADMIN_EMAIL . "\r\n";
                    $headers .= "Reply-To: " . ADMIN_EMAIL . "\r\n";
                    $headers .= "CC: " . ADMIN_EMAIL . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                  
                
                    sendMail($to, $subject, $message, $from, $headers);

                    SessionManager::setSessionVariable('success_message', 'Invoice was sent successfully.');
                    // Redirect to the invoice page
                    header('Location: ' . BASE_URL . '/admin/invoice/'.$orderId);
                    exit;
                                }
            

            } catch (\PDOException $ex) {
                error_log('PDO Exception: ' . $ex->getMessage());
            }

    }
}