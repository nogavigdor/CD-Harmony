
    <?php
    use services\SessionManager;
    include 'views/header.php';
    SessionManager::startSession();
    //setting a modal window of success message in case contact form was submitted successfully
    $successMessage = SessionManager::getSessionVariable('contact_output');
   

    if (!empty($successMessage['success'])) {
    ?>
    <div id="success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full md:w-1/2 p-8 rounded shadow-md">
            <span class="cursor-pointer text-gray-500 absolute top-2 right-2 text-xl" onclick="closeSuccessModal()">×</span>
            <p class="text-green-500"><?php echo $successMessage['success']; ?></p>
        </div>
    </div>
    <script defer>
        // JavaScript function to close the success modal
        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }
    </script>
    <?php
        // Clear the session variable
        SessionManager::unsetSessionVariable('contact_success');
    }
    ?>
    
    
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
