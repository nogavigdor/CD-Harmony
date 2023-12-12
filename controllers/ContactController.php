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
        try {
            if($_SERVER['REQUEST_METHOD']=="POST"){
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
              
    
                //if all the values of the error_output are nulls it means that no error message was return and the form is valid
                if (count(array_filter($error_output, 'is_null')) === count($error_output))  {
             
                if(isset($_POST['g-recaptcha-response'])){
                    $token = $_POST['g-recaptcha-response'];
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = array(
                        'secret' => RECAPTCHA_SECRET_KEY,
                        'response' => $token
                    );
            
                    $options = array(
                        'http' => array (
                            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                            'method' => 'POST',
                            'content' => http_build_query($data)
                        )
                    );
            
                    $context  = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);
                    $response = json_decode($result);
            
                    /*
                    - google response score is between 0.0 to 1.0
                    - if score is 0.5, it's a human
                    - if score is 0.0, it's a bot
                    - google recommend to use score 0.5 for verify human
                    */
                    if ($response->success && $response->score >= 0.5 && $response->action == 'contact') {
                        //upon a successful validation of the recaptcha, the email will be sent to the admin
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
                                        $mail->From = "contact@cdharmony.dk";
                                        $mail->addAddress("contact@cdharmony.dk", "Noga");
    
                                        $mail->Subject = $title;
                                        $mail->Body = "<h2 style='color:red;'>Email From:</h2> ".$email."<br />".$message;
                                        
                                        
                                        
                                        $mail->send();
                                        SessionManager::startSession();
                                        SessionManager::setSessionVariable('success_message', 'Your message has been sent successfully. We\'ll get back to you within 48 hours.');
                                        header('Location: ' . BASE_URL);
                                        exit();
                      //  echo json_encode(array('success' => true, "msg"=>"Your message has been sent successfully. We'll get back to you within 48 hours.", "response"=>$response));
                    } else {
            
                        //recaptcha validation faild
                        echo json_encode(array('success' => false, "msg"=>"There has been a problem with your contact form submission. Please try again later.", "response"=>$response));
                    }
                }  //fields validation has failed
            } else {
              SessionManager::setSessionVariable('error_message', 'Please check your form fields.');  
              $error_output['alert'] = 'Please check your form fields.';
              echo json_encode($error_output);  
            }
    /*
       
                if (isset($_POST['g-recaptcha-response'])) {
                    $captcha = $_POST['g-recaptcha-response'];
                    $recaptchaResult = $this->validator->validateRecaptchaResponse($captcha,'contact');
                }else {
                    $error_message = 'Please check the captcha form.';
                    SessionManager::startSession();
                    SessionManager::setSessionVariable('error_message', $error_message);
                    header('Location: ' . BASE_URL.'/contact');
                  }
             
    
                $recaptchaResult = $this->validator->validateRecaptchaResponse($captcha, 'contact');
                    echo 'I am after the recaptcha validation and the recaptcha result is: '.$recaptchaResult;
                // if all the values of the error_output are nulls it means that no error message was return and the form is valid
                if (count(array_filter($error_output, 'is_null')) === count($error_output))  {
                    //if the recaptcha validation was successfull
                        if ($recaptchaResult)  {
                                echo "I'm after the recaptch validation to true";
                             //    if (!($recaptchaResult->success)) {
                                    //updated output message to the user
                                    //$output = ['recaptcha' => 'Recaptcha validation was unsuccessfull. Pleaes try again later.'];
                                    
                                    // Sets a session variable for contact success
                                    $success_message =  'Your contact form has been submitted successfully.';
                                    SessionManager::startSession();
    
                                    SessionManager::setSessionVariable('success_message', $success_message);  
    
                            //setting up the email's settings in order to send the contact form information to the admin
                         
                          
                        
                                    // Send JSON response                           
                                    //redirection back to the contact form ti try again
                                    header('Location: ' . BASE_URL);
                                   
                                    exit();
                                
                                //The recaptcha validation was not successfull   
                          }else  {
                            // Sets a session variable for contact output
                            $contact_output['success'] =  'There has been a problem with your contact form submission. Please try again later';
                          // SessionManager::setSessionVariable('contact_output', $contact_output);
                             // Send JSON response
                            header('Location: ' . BASE_URL.'/contact');
                             exit();
                       }
       
                }
                 
                //input validtion was not successfull 
                 else    {
                  
                        $error_message = 'Please correct your form and try again';
                        SessionManager::startSession();
    
                        SessionManager::setSessionVariable('error_message', $error_message);
                        SessionManager::setSessionVariable('error_output', $error_output);
                        header('Location: ' . BASE_URL.'/contact');
                     exit();
                 } 
               
            
            } catch (\PDOException $ex) {
                // Log and handle any PDO Exceptions
                error_log('PDO Exception: ' . $ex->getMessage());
          
                exit();
            }
            */
        }
      
        }catch (\PDOException $ex) {
            // Log and handle any PDO Exceptions
            error_log('PDO Exception: ' . $ex->getMessage());
    
            exit();
        }
    }
}
