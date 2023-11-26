<?php


include 'header.php'; ?>

<div class="max-w-md mx-auto bg-white rounded p-6 shadow-md">
    <h1 class="text-2xl font-semibold mb-6">Registration Form</h1>

    <form id="registrationForm" method='POST' action='./signup' novalidate>
       
        <!-- Email -->
        <div class="mb-4 relative">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full border rounded px-3 py-2">
            <div class="absolute top-2 right-2">
                <!-- Add your email icon here -->
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <!-- Your icon code here -->
                </svg>
            </div>
            <div id="emailMessage" class="text-sm mt-1"></div> <!-- Dedicated message for email -->
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full border rounded px-3 py-2">
            <div class="absolute top-2 right-2">
                <!-- Add your password icon here -->
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <!-- Your icon code here -->
                </svg>
            </div>
            <div id="passwordMessage" class="text-sm mt-1"></div> <!-- Dedicated message for password -->
        </div>

        <!-- Confirm Password -->
        <div class="mb-4 relative">
            <label for="confirmPassword" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" class="w-full border rounded px-3 py-2">
            <div class="absolute top-2 right-2">
                <!-- Add your confirm password icon here -->
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <!-- Your icon code here -->
                </svg>
            </div>
            <div id="confirmPasswordMessage" class="text-sm mt-1"></div> <!-- Dedicated message for confirm password -->
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
            Register
        </button>
        <div class="text-red-500 text-sm mb-4">
    <?php
    $error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
    if (!empty($error_message)) {
        echo $error_message;
        unset($_SESSION['error_message']); // Clear the error message after displaying it
    }
    ?>
</div>
    </form>
    <div class="mt-4">
        <p>Already have an account? <a href="./login">Login here</a></p>
    </div>
</div>

<script src="/cdharmony/src/js/signupValidator.jsa"></script> 

<?php include 'footer.php'; ?>


    