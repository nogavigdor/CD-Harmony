<?php
namespace Controllers;

use Models\UserModel;
use \Services\Validator;
use \Services\SessionManager;

class UserController {
    
   

    public function __construct() {
        SessionManager::startSession();
        
    }
    
    public function createAccount() {
        //removes any while spaces from the begining and the end of the string 
        //and converts any special characters to HTML entities
        if (isset($_POST['first_name']))
          $first_name =htmlspecialchars(trim( $_POST['first_name']));
        if (isset($_POST['last_name']))
          $last_name = htmlspecialchars(trim( $_POST['last_name']));
        if (isset($_POST['email']))
            $email = htmlspecialchars(trim($_POST['email']));
        if (isset($_POST['password']))
        //not escaping the password as it will be hashed
            $password = trim($_POST['password']);
        if (isset($_POST['confirmPassword']))
        //not escaping the password as it will be hashed
            $confirmPassword = trim($_POST['confirmPassword']);
  
        $validator = new Validator();

        //as long as there are no errors the error type array will be empty
        $errType = [];
        
        //checks if both the psassword and email are valid for account creation
        //the method will return null in case the password is valid
        //otherwise it will return the error type

        $errType['password']=$validator->validatePassword($password);
        $errType['email']=$validator->validateEmail($email);
        if($password!=$confirmPassword) {
            $errType['passwordMatch']='Passwords do not match';
        }else{
            $errType['passwordMatch']=null;
        }

        //In case of null, there were no errors and the user will be created
        if (($errType['password']==null)&&($errType['email']==null)&&($errType['passwordMatch']==null)) {
            //creates a new instance of the user model (the model that handles the user data
              $userModel=new UserModel();

              //the method will return true in case the user was created successfully
            if( $userModel->setAccount($email, $password, $first_name, $last_name) )   
            {
                //if the user was created successfully, the user will be redirected to the login page
                SessionManager::setSessionVariable('success_message', 'Your user acount was created successfully. Please login.');
                header("Location: " . BASE_URL . "/login");
                exit();
            }
            else
            {
                $errType['general'] = 'Email already exists. Please try again with a different email.'; 
                //if the user was not created successfully, the user will be redirected to the signup page
                SessionManager::setSessionVariable('output_errors', $errType);
                header("Location: " . BASE_URL . "/signup");
                exit();
            }
         
        
        } 

        else //if there were errors, the user will be redirected to the signup page
        {
            $errType['general'] = 'Please correct your input fields and try again.';
            //the error messages will be stored in the session variables
            SessionManager::setSessionVariable('output_errors',$errType);
        
        header("Location: " . BASE_URL . "/signup");
        exit();
        }
    }

    public function authenticateUser() {

        //removes any while spaces from the begining and the end of the string  
        //and converts any special characters to HTML entities
        if (isset($_POST['email']))
            $email = htmlspecialchars(trim($_POST['email']));
        //not escaping the password as it will be hashed
        if (isset($_POST['password']))
             $password = trim($_POST['password']);
        
             
        $userModel = new UserModel();
        $user = $userModel->getAccount($email, $password);

        if ($user) {
           
            $userData = array(
                'logged_in' => true,
                'id' => $user['user_id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'first_name' => isset($user['first_name']) ? $user['first_name'] : "",
            );

            $success_message = "Hi " . $user['first_name'];
            
           
            //sets the success message in the session variable
            SessionManager::setSessionVariable('success_message', $success_message);  
            //sets the user data in the session variable
            SessionManager::setSessionVariable('user', $userData);        

                header("Location: " . BASE_URL . "/");
                exit();
            
        } else {
            $this->handleLoginError('Email does not exist or you have entered the wrong password.');
        }
    }

    private function handleLoginError($errorMessage) {
        SessionManager::setSessionVariable('error_message', $errorMessage);
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    public function signupView() {
        try {
            include 'views/signup.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
