<?php
require_once "bootstrap.php";
use Services\SessionManager;
SessionManager::startSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo BASE_URL ?>/src/css/output.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-primary font-body min-h-screen">
    <!-- Header section -->
    <header class="bg-secondary text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
        <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>

               <!-- Search area -->
            <div class="relative" >
            <input type="text" placeholder="Search products..." class="px-8 py-2 rounded-full border border-white bg-transparent text-buttonText focus:outline-none placeholder-gray-400">


                <button class="btn btn-primary search-button transition-transform hover:scale-105">
                    Search
                </button>
            </div>
            <nav class="space-x-4">
                <a href="#" class="text-white">Products</a>
                <a href="#" class="text-white">Articles</a>
                <a href="#" class="text-white">Special Offers</a>
                <a href="#" class="text-white">Orders</a>
                <a href="#" class="text-white">Company details</a>
            </nav>
           
            
        </div>

        
        <div class="container mx-auto flex justify-end align-end">
           
            <div>
                <button class="btn btn-primary">Shopping Cart</button>
                <button class="btn btn-secondary">Log In</button>
                <button class="btn btn-emphasis">Sign Up</button>
            </div>
        </div>
    </header>