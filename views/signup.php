<?php include 'header.php'; ?>

<div class="max-w-md mx-auto bg-white rounded p-6 shadow-md">
    <h1 class="text-2xl font-semibold mb-6">Registration Form</h1>

    <form id="registrationForm" method='POST' action='./signup'>
       
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
    </form>
    <div class="mt-4">
        <p>Already have an account? <a href="./login">Login here</a></p>
    </div>
</div>

<script>
document.getElementById('email').addEventListener('input', function () {
    validateEmail();
});

document.getElementById('password').addEventListener('input', function () {
    validatePassword();
});

document.getElementById('confirmPassword').addEventListener('input', function () {
    validateConfirmPassword();
});


function validateEmail() {
    let emailInput = document.getElementById('email');
    let emailMessage = document.getElementById('emailMessage');

    //checks if the email is valid
    if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        emailMessage.innerHTML = "Email is valid!";
        emailMessage.style.color = "green";
    } else {
        emailMessage.innerHTML = "Email should be valid.";
        emailMessage.style.color = "red";
    }
}

function validatePassword() {
    let password = document.getElementById('password').value;
    let passwordMessage = document.getElementById('passwordMessage');
    let isValid = true;

    // Minimum length of 8 characters
    if (password.length < 8) {
        passwordMessage.innerHTML = "Password must be at least 8 characters long.";
        isValid = false;
    } else {
        passwordMessage.innerHTML = "Password is valid!";
        passwordMessage.style.color = "green";
    }

    // Additional password validation checks...
    // At least one uppercase letter
    if (!/[A-Z]/.test(password)) {
        passwordMessage.innerHTML += "<br>Please include at least one capital letter.";
        isValid = false;
    }

    // At least one lowercase letter
    if (!/[a-z]/.test(password)) {
        passwordMessage.innerHTML += "<br>Please include at least one lowercase letter.";
        isValid = false;
    }

    // At least one digit
    if (!/\d/.test(password)) {
        passwordMessage.innerHTML += "<br>Please include at least one digit.";
        isValid = false;
    }

    // At least one special character
    if (!/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password)) {
        passwordMessage.innerHTML += "<br>Please include at least one special character.";
        isValid = false;
    }

    // Update message color based on overall validity
    if (isValid) {
        passwordMessage.style.color = "green";
    } else {
        passwordMessage.style.color = "red";
    }
}

function validateConfirmPassword() {
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let confirmPasswordMessage = document.getElementById('confirmPasswordMessage');

    // Confirm password match
    if (password === confirmPassword) {
        confirmPasswordMessage.innerHTML = "Passwords match!";
        confirmPasswordMessage.style.color = "green";
    } else {
        confirmPasswordMessage.innerHTML = "Passwords do not match.";
        confirmPasswordMessage.style.color = "red";
    }
}
</script>

<?php include 'footer.php'; ?>


    