<?php
namespace Controllers;

use \Services\Validator;
use \Services\SessionManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';
SessionManager::startSession();

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
                $error_output['last_name'] = $this->validator->validateName($last_name);
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
            $contact_output['errors'] = $error_output;
            
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
                } else {
                $captcha = false;
                SessionManager::setSessionVariable('error_message', 'There has been a problem submitting your form. Please try again later.');
                }
          
            $recaptchaResult = $this->validator->validateRecaptchaResponse($recaptcha, 'contact');

           
           
            // if all the values of the error_output are nulls it means that no error message was return and the form is valid
            if (count(array_filter($error_output, 'is_null')) === count($error_output)) {
                //if the recaptcha validation was successfull
                    if ($recaptchaResult->success)  {

                                //setting up the email's settings in order to send the contact form information to the admin
                             
                                try {
                                
                                    $mail = new PHPMailer(true);
                            
                                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                    $mail->isSMTP();
                                    $mail->SMTPAuth = true;
                                
                                    $mail->Host = "send.one.com";
                                    $mail->SMTPSecure = "ssl";
                                    
                                    $mail->Port = 465;

                                    $mail->Username = SMTP_USERNAME;
                                    $mail->Password = SMTP_PASSWORD;   
                                    //$mail->setFrom($email, $first_name);
                                    $mail->isHTML();
                                    $mail->From = "contact@cdhrmny.dk";
                                    $mail->addAddress("contact@cdhrmny.dk", "Noga");

                                    $mail->Subject = $title;
                                    $mail->Body = "<h2 style='color:red;'>Email From:</h2> ".$email."<br />".$message;
                                    
                                    
                                    
                                    $mail->send();

                            

                                } catch (Exception $e) {
                                    echo json_encode("Message could not be sent. Mailer Error: {$mail->ErrorInfo}"); 
                                }
                                
                                $success_message = 'Your message was sent successfully. We will get back to you as soon as possible.';
                                SessionManager::setSessionVariable('success_message', $success_message);
                                // Send JSON response   
                                // Send JSON response
                              //  header('Content-Type: application/json');
                               // echo json_encode($contact_output);                        
                                //redirection back to the contact form ti try again
                                header('Location: ' . BASE_URL);
                               
                                exit();
                          
                                //The recaptcha validation was not successfull
                            }else  {
                                 // Sets a session variable for contact output
                                 $error_message =  'There has been a problem with your contact form submission. Please try again later';
                                 SessionManager::setSessionVariable('error_message', $error_message);
                                  // Send JSON response
                                //  header('Content-Type: application/json');
                                 // echo json_encode($contact_output);
                                //  header('Location: ' . BASE_URL.'/contact');
                                  exit();
                            }
                        
                      }//end of isset recaptcha check
   
            
             
            //input validtion was not successfull 
             else    {
                  
                    SessionManager::setSessionVariable('error_message', 'Pease correct your input fields and try again.');
                    header('Content-Type: application/json');
                    //encoding the PHP array as JSON
                    echo json_encode($contact_output);  
                 exit();
             } 
           
        
        } catch (\PDOException $ex) {
            // Log and handle any PDO Exceptions
            error_log('PDO Exception: ' . $ex->getMessage());
            echo json_encode(['error' => 'An error occurred. Please try again later.']);
            exit();
        }
    }
    
    

    

 
}
