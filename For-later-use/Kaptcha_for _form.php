<?php
require_once('kaptcha/kaptcha.php');

$secretKey = 'YOUR_SECRET_KEY'; // Replace with your secret key
$kaptcha = new Kaptcha($secretKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaResponse = $_POST['g-recaptcha-response'];
    $response = $kaptcha->verify($captchaResponse);

    if ($response->isSuccess()) {
        // The CAPTCHA is valid, process your form data here.
        echo "CAPTCHA Verification Successful!";
    } else {
        // The CAPTCHA verification failed, handle it accordingly.
        echo "CAPTCHA Verification Failed!";
    }
}
?>

<form method="POST">
    <!-- Your other form fields go here -->
    <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
    <input type="submit" value="Submit">
</form>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
