<!DOCTYPE html>
<html lang="en">
<?php

include 'views/header.php';
?>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
        <h1 class="text-4xl text-center mt-8">reCAPTCHA V3 Demo</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <!-- Left Side: Image -->
            <div class="w-full h-auto">
                <img src=".././src/assets/images/martin-zaenkert-wcbBexGK0Rc-.jpg" alt="music fan" class="w-full h-full object-cover">
            </div>
            <!-- Right Side: Contact Form -->
            <form method="post" id="contact" class="bg-white rounded-lg shadow-lg p-4">
                <input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-element">
                        <input type="text" name="fname" id="fname" class="w-full p-4 rounded-lg border border-gray-300" placeholder="First Name *">
                        <div class="text-red-500">
                            Please enter your first name.
                        </div>
                    </div>
                    <div class="form-element">
                        <input type="text" name "lname" id="lname" class="w-full p-4 rounded-lg border border-gray-300" placeholder="Last Name *">
                        <div class="text-red-500">
                            Please enter your last name.
                        </div>
                    </div>
                </div>
                <div class="form-element mt-4">
                    <input type="text" name="email" id="email" class="w-full p-4 rounded-lg border border-gray-300" placeholder="Email Address *">
                    <div class="text-red-500">
                        Please enter your email address.
                    </div>
                </div>
                <div class="form-element mt-4">
                    <textarea name="message" id="message" class="w-full p-4 rounded-lg border border-gray-300" rows="4" placeholder="How can I help you? *"></textarea>
                    <div class="text-red-500">
                        Please enter a message.
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white font-bold rounded-lg p-4 mt-4" id="submit-button">Send Message</button>
                <p class="text-center mt-4">
                    <small>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</small>
                </p>
            </form>
        </div>
        <div id="alert" class="text-center mt-4 text-red-500"></div>
    </div>
    <?php
        include 'views/footer.php';
    ?>
</body>
</html>
