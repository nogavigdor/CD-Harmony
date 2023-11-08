
<!DOCTYPE html>
<html lang="en">
<?php

include 'header.php';
?>
<
<h1 class="text-center text-3xl font-bold mb-6">reCAPTCHA V3 Demo</h1>
    <div class="flex justify-center items-center h-scree max-w-screen-lg mx-auto px-4 py-6"> <!-- Set the maximum width on large and medium screens for the container -->
        <form method="post" id="contact" class="bg-white p-6 rounded-lg shadow-lg md:w-2/3 w-full transition-transform transform hover:scale-105">
            <input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
            
            <input type="text" name="fname" id="fname" class="w-full input input-bordered mt-4 focus:scale-105" placeholder="First Name *">
            <div class="text-red-500 text-sm mt-2 hidden">Please enter your first name.</div>
            
            <input type="text" name="lname" id="lname" class="w-full input input-bordered mt-4 focus:scale-105" placeholder="Last Name *">
            <div class="text-red-500 text-sm mt-2 hidden">Please enter your last name.</div>
            
            <input type="text" name="email" id="email" class="w-full input input-bordered mt-4 focus:scale-105" placeholder="Email Address *">
            <div class="text-red-500 text-sm mt-2 hidden">Please enter your email address.</div>
            
            <textarea name="message" id="message" class="w-full input input-bordered mt-4 focus:scale-105" placeholder="How can I help you? *"></textarea>
            <div class="text-red-500 text-sm mt-2 hidden">Please enter a message.</div>
            
            <button type="submit" class="w-full btn btn-primary mt-6 transition-transform transform hover:scale-105">Send Message</button>
            
            <div class="text-center mt-4">
                <small>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</small>
            </div>
        </form>
    </div>

   
    <?php

    include 'footer.php';
    ?>

</body>
</html>

