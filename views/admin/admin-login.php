<?php
use Services\SessionManager;
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
<body class="relative flex items-center justify-center min-h-screen">
<?php include 'partials/message.php';  ?>
<?php include 'partials/auto-loader.php'; ?>
<main class="container mx-auto my-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">

<section class="">
<div class="max-w-md mx-auto my-auto bg-white rounded p-6 shadow-md mt-20 mb-20">
    <h1 class="text-pink-600 text-2xl font-semibold mb-6">Admin Login</h1>

    <form id="loginForm" method='POST' action='./admin-login' novalidate>
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
            $errorMessage = SessionManager::getSessionVariable('output_errors')['general'] ?? '';
            if (!empty($errorMessage)) {
                echo "<p class='text-red-800''>$errorMessage</p>";
            }
        
            SessionManager::unsetSessionVariable('output_errors');
         
            ?>
    </form>

</div>
</section>

</main>
<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
    // Show the loader
    document.getElementById('loader').classList.remove('hidden');
});
</script>

</body>
</html>
