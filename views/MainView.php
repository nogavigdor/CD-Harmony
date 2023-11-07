<!DOCTYPE html>
<html lang="en">
 <?php



  include 'views/header.php';
 ?>
<body class="bg-#F9FBDF">
    <!-- Header section -->
    <header class="bg-#008080 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>"><img src="./src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>
            <nav class="space-x-4">
                <a href="#" class="text-white">Home</a>
                <a href="#" class="text-white">Shop</a>
                <a href="#" class="text-white">About</a>
                <a href="#" class="text-white">Contact</a>
            </nav>
        </div>
        <div class="container mx-auto flex justify-between items-center">
            <div>
                &copy; <?php echo date("Y"); ?> CD Harmony. All rights reserved.
            </div>
            <div>
                <button class="btn btn-primary">Shopping Cart</button>
                <button class="btn btn-secondary">Log In</button>
                <button class="btn btn-emphasis">Sign Up</button>
            </div>
        </div>
    </header>

    <!-- Main content section -->
    <main class="container mx-auto p-4">
        <!-- Pop CDs section -->
        <section>
            <h2 class="text-#13324E text-2xl font-bold mb-4">Pop CDs</h2>
    
                <?php
                // Include the "Pop CDs" section
                $controller = new \Controllers\ProductController();
               $controller->showProductsByTag('pop');
         
                ?>
       
        </section>
           <!-- Rock CDs section -->
           <section>
            <h2 class="text-#13324E text-2xl font-bold mb-4">Rock CDs</h2>
    
                <?php
                // Include the "Pop CDs" section
                $controller = new \Controllers\ProductController();
               $controller->showProductsByTag('rock');
    
                ?>
       
        </section>
         <!-- Country CDs section -->
         <section>
            <h2 class="text-#13324E text-2xl font-bold mb-4">Country CDs</h2>
    
                <?php
                // Include the "CountryCDs" section
                $controller = new \Controllers\ProductController();
               $controller->showProductsByTag('country');
                ?>
       
        </section>


        <!-- New Releases section -->
        <section>
            <h2 class="text-#13324E text-2xl font-bold mt-8 mb-4">New Releases</h2>
            <div class="grid grid-cols-4 gap-4">
                <?php
                // Include the "New Releases" section
                $controller->showRecentReleases();
                ?>
            </div>
        </section>

        <!-- Other content of your homepage -->
        <!-- ... -->
    </main>

    <!-- Footer section -->
    <?php
    include 'views/footer.php';
    ?>
</body>
</html>
