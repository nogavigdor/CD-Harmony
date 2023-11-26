

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


    */ 
    let contactUrl = BASE_URL+'/contact';
    console.log(contactUrl);

    isValid=true;
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
                console.log('checking BASE_URL with contact');

        

                //console.log(window.location.origin + 'contact');
                }
                grecaptcha.ready(function () {
                    console.log("I'm in the grecaptcha function");
                    grecaptcha.execute('6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8', {
                    
                    action: 'contact',
                    })
                        .then(function (token) {
                            let recaptchaResponse = document.getElementById("recaptchaResponse");
                            recaptchaResponse.value=token;
                            console.log("Form data:", new FormData(form));
                            console.log("Recaptcha Token:", recaptchaResponse.value);   
                            fetch(contactUrl,  {
                                method: "POST",
                                body: new FormData(form),
                            })
                            .then((response) => {

                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json(); // Parse JSON only if the response is OK
                            }
                                )
                            .then((response) => {
                            console.log(response);
                            //const responseText = JSON.parse(response) // Get the response
                            if(response.errors !== "") { // If there is an error
                            
                                //update email error message
                                emailError.classList.remove("hidden");      
                                emailError.innerHTML = response.errors.email;
                                //update title error message
                                titleError.classList.remove("hidden");
                                titleError.innerHTML = response.errors.title;
                                //update message error message
                                messageError.classList.remove("hidden");
                                messageError.innerHTML = response.errors.message;
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