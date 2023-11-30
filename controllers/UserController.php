<?php
namespace Controllers;

use Models\UserModel;
use \Services\Validator;
use \Services\SessionManager;

class UserController {
    
   private $email;
   private $password;

    public function __construct() {
        SessionManager::startSession();
        $this->email = htmlspecialchars(trim($_POST['email']));
        $this->password = htmlspecialchars(trim($_POST['password']));
    }
    
    public function createAccount() {
        //removes any while spaces from the begining and the end of the string
        if (isset($_POST['first_name']))
          $first_name =trim( $_POST['first_name']);
        if (isset($_POST['last_name']))
          $last_name = trim($_POST['last_name']);
  
        $validator = new Validator();

        //as long as there are no errors the error type will be null
        $errType = null;
        
        //checks if both the psassword and email are valid for account creation
        //the method will return null in case the password is valid
        //otherwise it will return the error type
        if ($errType=$validator->validatePassword($this->password)) 
            SessionManager::setSessionVariable('error_message',$errType);
        if ($errType=$validator->validateEmail($this->email))    
             SessionManager::setSessionVariable('error_message',$errType);

        //In case of null, there were no errors and the user will be created
        if ($errType==null) {
            //creates a new instance of the user model (the model that handles the user data
              $userModel=new UserModel();

              //the method will return true in case the user was created successfully
            if( $userModel->setAccount($this->email, $this->$password, $first_name, $last_name) )   
            {
                //if the user was created successfully, the user will be redirected to the login page
                SessionManager::setSessionVariable('success_message', 'Your user acount was created successfully. Please login.');
                header("Location: " . BASE_URL . "/login");
                exit();
            }
            else
            {
                //if the user was not created successfully, the user will be redirected to the signup page
                SessionManager::setSessionVariable('error_message', 'User could not be created. Please try again.');
                header("Location: " . BASE_URL . "/signup");
                exit();
            }
         
        
        }

       
    }

    public function authenticateUser() {

        $userModel = new UserModel();
        $user = $userModel->getAccount($this->email, $this->password);

        if ($user) {
           
            $userData = array(
                'logged_in' => true,
                'id' => $user['user_id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'first_name' => isset($user['first_name']) ? $user['first_name'] : "",
            );

            $success_message = "Hi " . $user['first_name'] . ", you have successfully logged in.";
            
           
            
            SessionManager::setSessionVariable('success_message', $success_message);     
            
            foreach ($userData as $key => $value) {
                SessionManager::setSessionVariable("user[$key]", $value);
            }
               

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
