

window.addEventListener("load", function () {

    "use strict";

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

        let contactUrl = BASE_URL+'/contact';

           

    
            if (alertElement) {
                alertElement.innerText = "Processing your submission, please wait...";
            }
        

            if (typeof grecaptcha === 'undefined') {
            console.error('reCAPTCHA script not loaded or encountered an error.');
            return;
            }else {
          
            //console.log(window.location.origin + 'contact');
            }
            grecaptcha.ready(function () {

                grecaptcha.execute('6LcTWQMpAAAAALPZfFSv0kU3vyQdooR0voAyTjb8', {
                
                action: 'contact',
                })
                    .then(function (token) {
                        let recaptchaResponse = document.getElementById("recaptchaResponse");
                        recaptchaResponse.value=token;
                     
                        for (const entry of new FormData(form)) {
                            const [key, value] = entry;
                        
                        }
                        
                       // console.log("Recaptcha Token:", recaptchaResponse.value);   
                        fetch(contactUrl,  {
                            method: "POST",
                            body: new FormData(form),
                        })
                        .then((response) => {
                            if (response.redirected) {
                                window.location.href = response.url; // Redirect if the response indicates a redirect
                                return null;
                            } else {
                                return response.json(); // Parse JSON only if the response is not a redirect
                            }
                        }
                            )
                        .then((response) => {
                          //to prevent the response being processed if the page is redirected
                            if(response!== null) { // If there is an error
                            
                                //update email error message
                                emailError.classList.remove("hidden");      
                                emailError.innerHTML = response.errors.email;
                                //update title error message
                                titleError.classList.remove("hidden");
                                titleError.innerHTML = response.errors.title;
                                //update message error message
                                messageError.classList.remove("hidden");
                                messageError.innerHTML = response.errors.message;
                                alertElement.innerText = 'Please correct the errors and try again.';
                            //  document.querySelector("#alert").innerText = responseText.error
                            // document.querySelector("#alert").classList.add("error")
                            //  document.querySelector(".formfields").style.display = "block"
                                return;
                            }
                        
                        })

                    })
                    .catch(function (error) {
                        console.error("reCAPTCHA execution error:", error);
                    });
            });
        
    });
});

function isValidName(name) {
    // Checks if it's a valid string.
    return /^[A-Za-z\s'-]+$/.test(name);
}