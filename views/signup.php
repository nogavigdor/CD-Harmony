    <?php
    include 'header.php'; 
    use Services\SessionManager; ?>    
<main class="container mx-auto my-auto px-4 flex flex-col  ">
    <div class=" w-full my-16 bg-white  rounded p-6 shadow-md lg:w-1/3 mx-auto">
        <h1 class="font-headline text-2xl font-semibold mb-6">Customer Registration Form</h1>

        <form id="registrationForm" method='POST' action='./signup' novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">        
            <!-- Email -->
            <div class="mb-4 relative">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?= SessionManager::getSessionVariable('email_input');?>" class="w-full border rounded px-3 py-2">
                <?php $this->session::unsetSessionVariable('email_input'); ?>
                <div class="absolute top-2 right-2">
                    <!-- Add your email icon here -->
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <!-- Your icon code here -->
                    </svg>
                </div>
                <div id="emailMessage" class=" text-sm mt-1">
                <?php
                // Display the email error message if it exists
                $emailErr = SessionManager::isVar('output_errors') && array_key_exists('email', SessionManager::getSessionVariable('output_errors')) ? SessionManager::getSessionVariable('output_errors')['email'] : '';
                    if ($emailErr!= null) {
                        echo $emailErr;
                    }
                ?>
                </div> 
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full border rounded px-3 py-2">
                <div class="absolute top-2 right-2">
               
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <!-- Your icon code here -->
                    </svg>
                </div>
                <div id="passwordMessage" class=" text-sm mt-1">
                <?php
                // Display the password error message if it exists
                    $passwordErr =  SessionManager::isVar('output_errors') && array_key_exists('password', SessionManager::getSessionVariable('output_errors')) ? SessionManager::getSessionVariable('output_errors')['password'] : '';
                    if ($passwordErr != null) {
                        echo $passwordErr;
                    }
                ?>

                </div> <!-- Dedicated message for password -->
            </div>

            <!-- Confirm Password -->
            <div class="mb-4 relative">
                <label for="confirmPassword" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="w-full  border rounded px-3 py-2">
                <div class="absolute top-2 right-2">
                    <!-- Add your confirm password icon here -->
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <!-- Your icon code here -->
                    </svg>
                </div>
                <div id="confirmPasswordMessage" class="text-sm mt-1  text-red-200 "></div> <!-- Dedicated message for confirm password -->

                <?php
                // Display the password error message if it exists
                    $passwordMatchErr = SessionManager::isVar('output_errors') && array_key_exists('passwordMatch', SessionManager::getSessionVariable('output_errors')) ? SessionManager::getSessionVariable('output_errors')['passwordMatch'] : '';
                    if ($passwordErr != null) {
                        echo $passwordMatchErr;
                    }
                
                ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn bg-accent text-white rounded px-4 py-2 hover:bg-orange-700">
                Register
            </button>
            <div class=" text-red-200  text-sm mb-4"> 
        <?php
        $error_message = SessionManager::isVar('output_errors') && array_key_exists('general', SessionManager::getSessionVariable('output_errors')) ? SessionManager::getSessionVariable('output_errors')['general'] : '';

            unset($_SESSION['output_errors']); // Clear the error message after displaying it

        ?>
    </div>
        </form>
        <div class="mt-4">
            <p>Already have an account? <a class="font-bold underline font-cta"href="./login">Login here</a></p>
        </div>
    </div>
</main>
    <script src="/cdharmony/src/js/signupValidator.js"></script> 

    <?php include 'footer.php'; ?>
