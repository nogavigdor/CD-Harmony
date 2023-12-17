<?php include 'header.php'; 
use \Services\SessionManager;
$errors_out = SessionManager::getSessionVariable('errors_output');
?>
<?php include 'partials/message.php'; ?>
<div class="max-w-md mx-auto bg-white rounded p-6 shadow-md mt-20 mb-20">
    <h1 class="font-headline text-2xl text-pink-800 font-semibold mb-6 ">Customer Login</h1>

    <form id="loginForm" method='POST' action='./login' novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">      
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
    </form>

    <div class="mt-4">
        <p>Don't have an account? <a text-accent text-xl href="./signup">Register here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>

