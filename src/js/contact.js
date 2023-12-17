
    function onSubmit(token) {
        const alertElement = document.querySelector("#alert");
        const emailError = document.getElementById("email").nextElementSibling;
        const titleError = document.getElementById("title").nextElementSibling;
        const messageError = document.getElementById("message").nextElementSibling;
        alertElement.innerText = "";
        emailError.classList.add("hidden");
        titleError.classList.add("hidden");
        messageError.classList.add("hidden");
        if (alertElement) {
            alertElement.innerText = "Processing your submission, please wait...";
        }
        
        const formData = new FormData(document.getElementById('contact_form'));
         formData.append('g-recaptcha-response', token);
        fetch('contact', {
            method: 'POST',
            body: formData
        })
            .then((response)=>{
                if (response.redirected) {
                    window.location.href = response.url; // Redirect if the response indicates a redirect
                    return null;
                } else {
                    return response.json(); // Parse JSON only if the response is not a redirect
                }
            })  
            .then(data => {
                if (data.success) {
                    // process to next request after success user is human
                  
                    window.location.href = BASE_URL;
                    console.log('Success', data);
                   
                } else {
           
                    // process to next request after failed user is not human
                    console.error('Server Side Erorr', data);
                        //update email error message
                        emailError.innerText = data.email;
                        emailError.classList.remove("hidden");
                        //update title error message
                        titleError.innerText = data.title;
                        titleError.classList.remove("hidden");
                        //update message error message
                        messageError.innerText = data.message;
                        messageError.classList.remove("hidden");
                        //update alert message
                        alertElement.innerText = data.alert;
                }
            })
            .catch(error => {
                console.error('Client Side Error', error);
            });
    }


