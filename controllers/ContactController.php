<?php
namespace controllers;

use Services\Validator;

use Services\SessionManager;

class ContactController 
{
    private $validator;

    public function __construct() {
        $this->validator = new Validator();
    }

    public function contactView()
    {
        try {
            // Load the view to display the products
            include_once 'views/contact.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
    public function contactInput()
    {
        ini_set('display_errors', 1);
            error_reporting(E_ALL);
        try {
            

            error_log('ContactController.contactInput method reached');
            // Declaring variables to prepare for form submission
            $success_output = '';
            $error_output = [];
    
            // Validate individual form fields
            if (isset($_POST['first_name'])) {
                $first_name = $_POST['first_name'];
                $error_output['first_name'] = $this->validator->validateName($first_name);
            }
            else 
            //first name field is not mandatory, therefor if its not set, it can pass the check with a null in the error_output
            $error_output['first_name'] = '';

            
            if (isset($_POST['last_name'])) {
                $last_name = $_POST['last_name'];
                $error_output['lastt_name'] = $this->validator->validateName($last_name);
            }
            else 
            //last name field is not mandatory, therefor if its not set, it can pass the check with a null in the error_output
            $error_output['last_name'] = '';

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $error_output['email'] = $this->validator->validateEmail($email);
            }
            else 
            //no email is set therefor an invalid email message should put in the errors_output
            $error_output['email'] = 'Please enter your email';

            if (isset($_POST['title'])) {
                $title = $_POST['title'];
                $error_output['title'] = $this->validator->validateName($title);
            }
            else 
            //no title is set therefor an invalid email message should put in the errors_output
            $error_output['title'] = 'Please choose a topic';

            if (isset($_POST['message'])) {
                $message = $_POST['message'];
                $error_output['message'] = $this->validator->validateMessage($message);
            }
            else 
            //no message is set therefor an invalid email message should put in the errors_output
            $error_output['message'] = 'Please write your message';


    

    
            // if all the values of the error_output are nulls it means that no error message was return and the form is valid
            if (count(array_filter($error_output, 'is_null')) === count($error_output))  {
                // Proceeds with reCAPTCHA verification
               // $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; // URL to the reCAPTCHA server
               // $recaptcha_secret = '6LcTWQMpAAAAAEq-qGXtn9Iy_kuAcv8_AEwZxfqH'; // Secret key
                //$recaptcha_response = $_POST['recaptchaResponse']; // Response from reCAPTCHA server, added to the form during processing
               //$recaptcha = file_get_contents($recaptcha_url.'?secret='.RECAPTHA_SECRET_KEY.'&response='.$recaptcha_response); // Send request to the server
               // $recaptcha = json_decode($recaptcha); // Decode the JSON response
             //   error_log('Form Data: ' . print_r($_POST, true));
                //error_log('Error Output: ' . print_r($error_output, true));

                
                $recaptcha = $this->validator->validateRecaptchaResponse();
                
                //if recaptcha verification succeed
                if($recaptcha)
                {

                     /*
                            
                            // Set the SMTP server and port for One.com
                            ini_set('SMTP', 'send.one.com');
                            ini_set('smtp_port', 465);

                            // Enable SMTP authentication
                            ini_set('smtp_auth', true);

                            // Set the SMTP username and password (your One.com email credentials)
                            ini_set('smtp_username', 'contact@cdharmony.dk');
                            ini_set('smtp_password', 'Venus999@');

                            // Set encryption type (SSL/TLS)
                            ini_set('smtp_crypto', 'tls'); // or 'tls'

                            // Other mail-related settings
                            ini_set('sendmail_from', 'contact@cdharmony.dk');
                            ini_set('mail.add_x_header', 'On');
                        */                       
                        $to      = 'noga.vigdor@gmail.com';
                        $subject = $title;
                        $message = $message;
                        $headers = array(
                            'From' => $email,
                            'Reply-To' => $email,
                            'X-Mailer' => 'PHP/' . phpversion()
                        );

                    // runs email send routine  
                    $mailSent = mail($to, $subject, $message, $headers);
               
                    if ($mailSent) {
                        $success_output = 'Your message was sent successfully.';
                    } else {
                        $error_output['email_configuration'] = 'Error sending email. Check your server configuration.';
                    }

                       // Start the session
                      SessionManager::startSession();
                     
                        // Sets a session variable for contact success
                        SessionManager::setSessionVariable('contact_success', true);

                        // Redirect to the homepage
                       header('Location: ' . BASE_URL);

                        exit();
                }
               
                 else {
                    $output = ['recaptcha' => 'Recaptcha validation was unsuccessfull. Pleaes try again later.'];

                 }
                 //recaptcha was not successfull
               
            }
            //There was an invalid output
            // Output error or success message
            $output = [
                'error' => $error_output,
                'success' => $success_output,
                
            ];
    
            // Send JSON response
         //   header('Content-Type: application/json');
            echo json_encode($output);
        } catch (\PDOException $ex) {
            // Log and handle any PDO Exceptions
            error_log('PDO Exception: ' . $ex->getMessage());
            echo '<div class="error-message">An error occurred. Please try again later.</div>';
        }
    }
    
    

    

 
}
