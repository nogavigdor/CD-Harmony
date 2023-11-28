<?php include 'header.php'; ?>

<div class="max-w-md mx-auto bg-white rounded p-6 shadow-md">
    <h1 class="text-2xl font-semibold mb-6">Login</h1>

    <form id="loginForm" method='POST' action='./login'>
        <!-- Email -->
        <div class="mb-4 relative">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full border rounded px-3 py-2">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
            Login
        </button>
            <?php
            // Display the error message if there is one
            $errorMessage = SessionManager::getSessionVariable('error_message')?? '';
            if (!empty($errorMessage)) {
                echo "<p style='color: red;'>$errorMessage</p>";
            }
        
            SessionManager::unsetSessionVariable('error_message');
         
            ?>
    </form>

    <div class="mt-4">
        <p>Don't have an account? <a href="./signup">Register here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
