<?php include 'header.php'; ?>
<?php use Services\SessionManager; 
 SessionManager::startSession(); ?>


    <h1 class="text-center text-3xl font-bold mb-6">Contact Us</h1>

    <div class="flex justify-center items-center h-screen max-w-screen-lg mx-auto px-4 py-6">
        <!-- Set the maximum width on large and medium screens for the container -->
        <form  novalidate id="contact_form" method="POST" action="<?= BASE_URL.'/contact'; ?>"  class="bg-white p-6 rounded-lg shadow-lg md:w-2/3 w-full transition-transform transform hover:scale-105">
            <input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
            
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">Please enter your first name</label>
                <input type="text" name="first_name" id="first_name" class="w-full input input-bordered mt-1 focus:scale-105">
                <div class="text-red-500 text-sm mt-2 error hidden">Please enter a valid first name.</div>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Please enter your last name</label>
                <input type="text" name="last_name" id="last_name" class="w-full input input-bordered mt-1 focus:scale-105">
                <div class="text-red-500 text-sm mt-2 error hidden">Please enter a valid last name.</div>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">* Please enter your email</label>
                <input type="text" name="email" id="email" required class="w-full input input-bordered mt-1 focus:scale-105">
                <div class="text-red-500 text-sm mt-2 error hidden">Please enter a valid email address.</div>
            </div>

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">* Please choose a topic</label>
                <select name="title" id="title" required class="w-full input input-bordered mt-1 focus:scale-105">
                    <option value="" disabled selected>Choose a Topic</option>
                    <option value="Order Inquiry">General Inquiry</option>
                    <option value="Order Inquiry">Order Inquiry</option>
                    <option value="Product Availability">Product Availability</option>
                    <option value="Returns and Exchanges">Returns and Exchanges</option>
                    <option value="Business Partnerships">Business Partnerships</option>
                    <option value="Feedback and Suggestions">Feedback and Suggestions</option>
                    <option value="other">Other</option>
                </select>
                <div class="text-red-500 text-sm mt-2 error hidden">Please choose a topic.</div>
            </div>

            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">* Please enter your message</label>
                <textarea name="message" id="message" required class="w-full input input-bordered mt-1 focus:scale-105 h-32 resize-y"></textarea>
                <div class="text-red-500 text-sm mt-2 error hidden">Please enter a message.</div>
            </div>

            <button type="submit" class="w-full btn btn-primary mt-6 transition-transform transform hover:scale-105">Send Message</button>
            <div class="mt-4 mb-4" id="alert">
            </div>
            <div class="text-center mt-4">
                <small>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</small>
            </div>
        </form>
    </div>

        <?php
    // Unset the session variable

    unset($_SESSION['contact_output']);
    ?>                

    <?php include 'footer.php'; ?>