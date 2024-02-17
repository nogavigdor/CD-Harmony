<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo BASE_URL ?>/dist/output.css" rel="stylesheet">
  <script deffer>src="<?php echo BASE_URL ?>/src/js/app.js"</script>    
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-primary font-body min-h-screen">
    <!-- Header section -->
    <header class="bg-secondary text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>

        <!-- Search area -->
        <div class="relative">
            <input type="text" placeholder="Search products..." class="px-8 py-2 rounded-full border border-white bg-transparent text-buttonText focus:outline-none placeholder-gray-400">

            <button class="btn btn-primary search-button transition-transform hover:scale-105">
                Search
            </button>
        </div>

        <nav class="space-x-4">
            <a href="<?php echo BASE_URL ;?>" class="text-white">Home</a>
            <a href="<?php echo BASE_URL ;?>" class="text-white">Shop</a>
            <a href="#" class="text-white">About</a>
            <a href="<?php echo BASE_URL ;?>/contact" class="text-white">Contact</a>
        </nav>

        <div class="flex items-center">
            <!-- Other header elements... -->

            <!-- Login Button -->
            <a href="<?php echo BASE_URL ;?>/login" class="ml-4 btn btn-secondary">Login</a>

            <!-- Sign Up Button -->
            <a href="<?php echo BASE_URL ;?>/signup" class="ml-4 btn btn-primary">Sign Up</a>

           
        </div>
    </div>
</header>




    <h1 class="text-center text-3xl font-bold mb-6">Contact Us</h1>

    <div class="flex justify-center items-center h-screen max-w-screen-lg mx-auto px-4 py-6">
        <!-- Set the maximum width on large and medium screens for the container -->
        <form  novalidate id="contact_form" method="post" action='./contact' class="bg-white p-6 rounded-lg shadow-lg md:w-2/3 w-full transition-transform transform hover:scale-105">
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
            <div mt-4 mb-4 id="alert">
            </div>
            <div class="text-center mt-4">
                <small>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</small>
            </div>
        </form>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?render=6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8"></script>

        <script>
        console.log('hi');

        window.addEventListener("load", function () {
            console.log("Script loaded");

            "use strict";

            console.log("I'm in the loading function");

            const form = document.querySelector("#contact_form");

            form.addEventListener("submit", function (event) {
                event.preventDefault();
                const alertElement = document.querySelector("#alert");
                const emailError = document.getElementById("email").nextElementSibling;
                const titleError = document.getElementById("title").nextElementSibling;
                const messageError = document.getElementById("message").nextElementSibling;
                alertElement.innerText = "";
                emailError.classList.add("hidden");
                titleError.classList.add("hidden");
                messageError.classList.add("hidden");
/*
                let fields = document.querySelectorAll("#contact_form [required]");
                let isValid = true;

                fields.forEach(function (field) {
                    const errorElement = field.nextElementSibling;
                    errorElement.classList.add("hidden");
                    field.classList.remove('border', 'border-red-500');

                    if (field.value === "") {
                        field.classList.add('border', 'border-red-500');
                        errorElement.classList.remove("hidden");
                        isValid = false;
                    }
                });

                const firstName = document.getElementById("first_name");
                const lastName = document.getElementById("last_name");

                if (!isValidName(firstName.value)) {
                    const firstNameError = document.getElementById("first_name").nextElementSibling;
                    if (firstNameError) {
                        firstNameError.classList.remove("hidden");
                    }
                    firstName.classList.add('border', 'border-red-500');
                    isValid = false;
                }

                if (!isValidName(lastName.value)) {
                    const lastNameError = document.getElementById("last_name").nextElementSibling;
                    if (lastNameError) {
                        lastNameError.classList.remove("hidden");
                    }
                    lastName.classList.add('border', 'border-red-500');
                    isValid = false;
                }

                const email = document.getElementById("email");
                const emailPattern = /^[^0-9][A-z0-9_-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/;
                if (!emailPattern.test(email.value)) {
                    const emailError = document.getElementById("email").nextElementSibling;
                    if (emailError) {
                        emailError.classList.remove("hidden");
                    }
                    email.classList.add('border', 'border-red-500');
                    isValid = false;
                }

                const title = document.getElementById("title");
                if (title.value === "") {
                    const titleError = document.getElementById("title").nextElementSibling;
                    if (titleError) {
                        titleError.classList.remove("hidden");
                    }
                    title.classList.add('border', 'border-red-500');
                    isValid = false;
                }

                const message = document.getElementById("message");
                if (message.value.length > 2000) { // Adjust the max length as needed
                    const messageError = document.getElementById("message").nextElementSibling;
                    if (messageError) {
                        messageError.classList.remove("hidden");
                    }
                    message.classList.add('border', 'border-red-500');
                    isValid = false;
                }
*/ isValid=true;
                if (isValid) {
                    console.log("I'm in the valid function");

              
                    if (alertElement) {
                        alertElement.innerText = "Processing your submission, please wait...";
                    }

                    console.log("Before grecaptcha.ready");
                    if (typeof grecaptcha === 'undefined') {
        console.error('reCAPTCHA script not loaded or encountered an error.');
        return;
    }else {
        console.log(grecaptcha);

        //console.log(window.location.origin + 'contact');
    }
                    grecaptcha.ready(function () {
                        console.log("I'm in the grecaptcha function");
                        grecaptcha.execute("6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8", {
                        
                        action: 'contact',
                        })
                            .then(function (token) {
                                let recaptchaResponse = document.getElementById("recaptchaResponse");
                                recaptchaResponse.value=token;
                                console.log("Form data:", new FormData(form));
                                fetch("<?php echo BASE_URL ?>/contact",  {
                                    method: "POST",
                                    body: new FormData(form),
                                })
                                .then((response) => response.text())
                                .then((response)=> {
                                    const responseText = JSON.parse(response);
                                   if(responseText.error !== "") { // If there is an error
                                   
                                  
                                    //update email error message
                                    emailError.classList.remove("hidden");
                                    emailError.innerHTML = responseText.error.email;
                                    //update title error message
                                    titleError.classList.remove("hidden");
                                    titleError.innerHTML = responseText.error.title;
                                    //update message error message
                                    messageError.classList.remove("hidden");
                                    messageError.innerHTML = responseText.error.message;
                                  //  document.querySelector("#alert").innerText = responseText.error
                                   // document.querySelector("#alert").classList.add("error")
                                  //  document.querySelector(".formfields").style.display = "block"
                                    return
                                }
                                
                                })

                            })
                            .catch(function (error) {
                                console.error("reCAPTCHA execution error:", error);
                            });
                    });
                }
            });
        });

        function isValidName(name) {
            // Checks if it's a valid string.
            return /^[A-Za-z\s'-]+$/.test(name);
        }
    </script>


<footer class="flex-col p-10 bg-base-200 text-base-content">
<div class="footer">
<?php
            
$controller = new \Controllers\CompanyController();
$company=$controller->getCompanyDetails();
?>
            
 <!-- Company Details -->
 <div>
  <header class="footer-title">Company Details</header>
  <p><?php echo $company->company_name; ?></p>
  <p>Address: <?php echo $company->street." ".$company->postal_code_id." ".$company->city; ?></p>
  <p>Email: <a href="mailto:<?php echo $company->email; ?>"><?php echo $company->email; ?></a></p>
  <p>Phone Number: <?php echo $company->phone_number; ?></p>
  <p>Opening Hours: <?php echo $company->opening_hours; ?></p>
  </div>
  <nav>
    <header class="footer-title">Services</header>
    <a class="link link-hover">Branding</a>
    <a class="link link-hover">Design</a>
    <a class="link link-hover">Marketing</a>
    <a class="link link-hover">Advertisement</a>
  </nav>
  <form>
    <header class="footer-title">Newsletter</header>
    <fieldset class="form-control w-80">
      <label class="label">
        <span class="label-text">Enter your email address</span>
      </label>
      <div class="relative">
        <input type="text" placeholder="username@site.com" class="input input-bordered w-full pr-16" />
        <button class="btn btn-primary absolute top-0 right-0 rounded-l-none">Subscribe</button>
      </div>
    </fieldset>
  </form>  
 
</div> <!--End of footer columns -->
  <div class="flex justify-center mt-8 font-headline">&copy; <?php echo date("Y"); ?> <span class="mr-4 ml-4"><?php echo $company->company_name; ?></span>All rights reserved. </div>
 
</footer>



</body>
</html>
