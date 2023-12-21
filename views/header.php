<?php
use Services\SessionManager; 
include './utilities/functions.php';
$session = new SessionManager();
$session->startSession();
$csrfToken=SessionManager::generateCSRFToken();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASE_URL ?>/src/css/output.css" rel="stylesheet">
    <script defer src="<?php echo BASE_URL; ?>/src/js/constants.js"></script> 
    <script defer src="<?php echo BASE_URL; ?>/src/js/app.js"></script>   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <?php
        // Include view-specific JavaScript files based on the last part of the URL
        $currentView = getCurrentView();
        
        if ($currentView == 'contact') {
            echo '<script defer src="https://www.google.com/recaptcha/api.js"></script>';
            $jsFileName = 'contact.js';
            echo '<script defer src="' . BASE_URL . '/src/js/' . $jsFileName . '"></script>';}
        
        if ($currentView == 'signup') {
             echo '<script defer src="https://www.google.com/recaptcha/api.js"></script>';
             $jsFileName = 'signup.js';
            echo '<script defer src="' . BASE_URL . '/src/js/' . $jsFileName . '"></script>';}

        
    ?>

    </head>
    <body class="bg-primary  font-body min-h-screen">
   
     <!-- Header -->
    <header class="relative bg-secondary text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
          <div>  <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64"></a></div>
        <?php    
       if($currentView != 'login' && $currentView != 'signup') { ?>
       
          <!-- Search area 
          <div class="relative">
                <input type="text" placeholder="Search products..." class="px-8 py-2 rounded-full border border-white bg-transparent text-buttonText focus:outline-none placeholder-gray-400">

                <button class="btn btn-primary search-button transition-transform hover:scale-105">
                    Search
                </button>
            </div>
           -->
            <!-- Navigation Links -->
            <nav  class="space-x-4 text-xl">
                <a href="<?php echo BASE_URL; ?>" class="text-white">Home</a>
                <a href="<?php echo BASE_URL; ?>" class="text-white">Shop</a>
                <a href="<?= BASE_URL.'/cart';  ?>" class="text-white">Cart</a>
                <a href="<?php echo BASE_URL; ?>/contact" class="text-white">Contact</a>
            </nav>

            <!-- User and Cart Section -->
            <div class="relative flex items-center">
                 <!-- Cart Button -->
                 <a href="<?php echo BASE_URL;?>">CART</a>
                <a href="<?php echo BASE_URL; ?>/cart" class="ml-4 btn btn-secondary relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    <?php
                        
                        
                        //Calculate the total quantity of items in the cart
                        //in ourder to update the cart icon
                        $cart = SessionManager::getSessionVariable('cart');
                       
                        $total_qty=0;
                        if(is_array($cart)){
                            
                            foreach ($cart as $productVarId => $cartItem) {
                              
                                if(isset($cartItem['quantity'])){
                                    $total_qty+=$cartItem['quantity'];
                                }
                                
                            }
                        } 
                    ?>
                    <span id="cartItemCount" class="absolute -top-6 -right-4 bg-base-100 text-secondary text-xl rounded-full h-12   w-12 flex items-center justify-center"><?= $total_qty?></span>
                </a>

                <?php
                // Login Button
                if (SessionManager::isLoggedIn()) {  ?>
       
                    
                <!-- User Symbol -->
                <button id="userBtn" class="ml-4 relative z-10 w-10 h-10 rounded-full overflow-hidden border-2 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                   <!--For future use when account area is completed -->
                   <!-- <img src="user-avatar.jpg" alt="User Avatar" class="w-full h-full object-cover">  -->
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
</svg>

                </button>

                <!-- User Menu -->
                <div id="menu" class="hidden absolute top-[50px]  right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                    <a href="<?= BASE_URL . '/account';?>" class="block px-4 py-2 text-gray-800 hover:bg-orange-200 hover:text-gray-600">Account</a>
                    <a href="<?= BASE_URL . '/logout'; ?>"class="block px-4 py-2 text-gray-800 hover:bg-orange-200 hover:text-gray-600">Logout</a>
                </div>
                <?php   } else {
                    echo '<a href="' . BASE_URL . '/login" class="ml-4 btn btn-secondary">Login</a>';
                    echo '<a href="' . BASE_URL . '/signup" class="ml-4 btn btn-primary">Sign Up</a>';
                }
                ?>
          

            </div>
            <?php } ?>
    
          
        </div>
    </header>   
    <?php include 'partials/message.php'; //integrating success and error messages for the use  ?>
    <?php include 'partials/auto-loader.php' ?>