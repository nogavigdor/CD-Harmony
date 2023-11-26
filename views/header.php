    <?php
    use Services\SessionManager;
    SessionManager::startSession();

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASE_URL ?>/dist/output.css" rel="stylesheet">
    <script defer src="<?php echo BASE_URL; ?>/src/js/constants.js"></script> 
    <script defer src="<?php echo BASE_URL; ?>/src/js/app.js"></script>   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <?php
        // Include view-specific JavaScript files based on the last part of the URL
        $url = $_SERVER['REQUEST_URI'];
        $parts = explode('/', rtrim($url, '/'));
        $currentView = end($parts);
        
        if ($currentView == 'contact') {
            echo '<script defer src="https://www.google.com/recaptcha/api.js?render=6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8"></script>';
            $jsFileName = 'contact.js';
            echo '<script defer src="' . BASE_URL . '/src/js/' . $jsFileName . '"></script>';}
        
        if ($currentView == 'signup') {
             echo '<script defer src="https://www.google.com/recaptcha/api.js?render=6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8"></script>';
             $jsFileName = 'signup.js';
            echo '<script defer src="' . BASE_URL . '/src/js/' . $jsFileName . '"></script>';}
    ?>

    </head>
    <body class="bg-primary font-body min-h-screen">
        <!-- Header section -->
        <header class="bg-secondary text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>

            <!-- Search area -->
            <div class="relative">
                <input type="text" placeholder="Search products..." class="px-8 py-2 rounded-full border border-white bg-transparent text-buttonText focus:outline-none placeholder-gray-400">

                <button class="btn btn-primary search-button transition-transform hover:scale-105">
                    Search
                </button>
            </div>

            <nav class="space-x-4">
                <a href="<?php echo BASE_URL ;?>" class="text-white">Home</a>
                <a href="<?php echo BASE_URL ;?>" class="text-white">Shop</a>
                <a href="#" class="text-white">About</a>
                <a href="<?php echo BASE_URL ;?>/contact" class="text-white">Contact</a>
            </nav>

            <div class="flex items-center">
                <!-- Other header elements... -->

                <!-- Login Button -->
                <a href="<?php echo BASE_URL ;?>/login" class="ml-4 btn btn-secondary">Login</a>

                <!-- Sign Up Button -->
                <a href="<?php echo BASE_URL ;?>/signup" class="ml-4 btn btn-primary">Sign Up</a>

            
            </div>
        </div>
    </header>