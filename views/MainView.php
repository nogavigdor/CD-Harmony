
 <?php



  include 'views/header.php';
 ?>

  
<body>
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


        <!-- New Arrivals section -->
        <section>
            <h2 class="text-#13324E text-2xl font-bold mt-8 mb-4">New Arrivals</h2>
         
                <?php
                // Include the "New Releases" section
                $controller = new \Controllers\ProductController();
                $controller->showRecentReleases();
                ?>
          
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
