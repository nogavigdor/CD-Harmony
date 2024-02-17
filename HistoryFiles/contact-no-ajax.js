

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


        const errors=document.getElementById("isError").value;
        console.log(errors);
           

    
            if (alertElement) {
                alertElement.innerText = "Processing your submission, please wait...";
            }
        

          
        
    });
});

function isValidName(name) {
    // Checks if it's a valid string.
    return /^[A-Za-z\s'-]+$/.test(name);
}