<?php
namespace Controllers;
use Models\ProductModel;
use Models\OrderModel;
use Services\SessionManager;
use Services\EmailService;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Stripe;

// Include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);


class CheckoutController {
    private $session;
    private $orderModel;
    public function __construct() {
        $this->session = new SessionManager();
        $this->orderModel = new OrderModel();
        $this->session->startSession();
    }

    public function stripeCheckout(){
        try{

            if (!SessionManager::isCustomer()) {
                // User is not logged in as a customer, redirect to the login page
                SessionManager::setSessionVariable('alert_message', 'Please login in order to checkout.');
                header('Location:'. BASE_URL. '/login');
                exit;
            }
        

            $cart = SessionManager::getSessionVariable('cart');
  
            $lineItems = [];
            $total = 0;
   
           
            // Convert price from string to float
            foreach ($cart as $product_variant_id => $cartItem) {
                 // Check if the current item is one of the additional keys
                if (!is_numeric($product_variant_id)) {
                    continue; // Skip this iteration if it's not a product item
                }

                // Cast the price to float and quantity to integer
                $price = (float) $cartItem['price'];
                $quantity = (int) $cartItem['quantity'];
                 // Log the quantity for debugging

                // Create the line item with the correctly typed values
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'dkk',
                        'product_data' => [
                            'name' => $cartItem['product_title'],
                        ],
                        'unit_amount' => $price * 100,
                    ],
                    'quantity' => $quantity,
                ];
            
                // Calculate the total
                $total += $price * $quantity;
            }

       
       

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => BASE_URL . '/order-confirmation',
                'cancel_url' => BASE_URL . '/cart',
            ]);


             // Retrieve the paymentIntentId from the checkout session
              //  $paymentIntentId = $checkoutSession->payment_intent;


             // Redirect the user to the Stripe checkout page
            header('Location: ' . $checkoutSession->url);
            exit;
          
        }catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //for future implementation - not used in this project
    //this function is used to handle the stripe webhook events
    public function stripeWebhook(){
        // Retrieve the request's body and parse it as JSON
        $input = file_get_contents('php://input');
        $event = json_decode($input);

        // Verify the webhook signature
        $webhookSecret = STRIPE_WEBHOOK_SECRET; 
        $signatureHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = Webhook::constructEvent($input, $signatureHeader, $webhookSecret);
        } catch (\Exception $e) {
            http_response_code(400);
            exit();
        }

        // Handle the event
        //I've built it as switch statement in order to handle different type of events
        //in case I'll need to add more events in the future
        switch ($event->type) {
            case 'payment_intent.refunded':
                // A payment intent has been refunded
                $paymentIntent = $event->data->object;
                $paymentIntentId = $paymentIntent->id;
                
                // Update your database to reflect the refund status
                // Example: Update the order status or record the refunded amount
                $this->orderModel->updateOrderStatus($paymentIntentId);
                break;
          default:
                // Handle other event types as needed
                break;
        }

        // Send a 200 response to acknowledge receipt of the event
        http_response_code(200);

    }

    public function checkoutView(){

        try{

            include 'views/checkout.php';
        }catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }

    }


//This checkout function is not used in this project.
//It is used to handle the checkout process without using stripe
    public function checkout() {
 
        // Check if the user is logged and  a customer before checkout
        if (!SessionManager::isCustomer()) {
            // User is not logged in as a customer, redirect to the login page
;
            SessionManager::setSessionVariable('error_message', 'Please login in order to checkout.');
            header('Location: /login');
            exit;
        }
        //user is logged in as a customer and therefore checkout is allowed
        // Check if the cart is not empty and if there is at least one product variant in the cart
            if (SessionManager::isVar('cart') && count(SessionManager::getSessionVariable('cart')) > 0) {
                $cart = SessionManager::getSessionVariable('cart');

                $userId = isset($user_details['id']) ? $user_details['id'] : null;  // Adjust the key based on your user data structure
         


                    //after the order is created a trigger define in MySQL updates the stock quantaties.
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