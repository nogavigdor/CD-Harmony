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
    if (/^[^0-9][A-z0-9_-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/.test(emailInput.value)) {
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