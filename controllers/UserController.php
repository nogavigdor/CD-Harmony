<?php
namespace Controllers;

use Models\UserModel;
use \Services\Validator;
use \Services\SessionManager;

class UserController {
    
   private $session;

    public function __construct() {
        $session = new SessionManager();
        $session->startSession();
        $this->session = $session;
        
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

    //user (customer) login
    public function authenticateUser() {

        //removes any while spaces from the begining and the end of the string  
        //and converts any special characters to HTML entities
        if (isset($_POST['email']))
        $email = htmlspecialchars(trim($_POST['email']));
        //not escaping the password as it will be hashed
        if (isset($_POST['password']))
        $password = trim($_POST['password']);
        //sets the role to 3 (customer)
        $role = 3;
        $userModel = new UserModel();
        $user = $userModel->getAccount($email, $role);
 
        //checks if the password matches the hashed password in the database    
        if ($user && (password_verify($password, $user['user_password']))) {

            $userData = array(
                'logged_in' => true,
                'id' => $user['user_id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'first_name' => isset($user['first_name']) ? $user['first_name'] : "",
            );

            //sets the user data in the session variable
            SessionManager::setSessionVariable('user', $userData);

            $success_message = "Hi " . $user['first_name']. ' you are now logged in';
            SessionManager::setSessionVariable('success_message', $success_message);
            header("Location: " . BASE_URL . "/");
            exit();
        } else {
            //sets the input fields in the session variable so when redirected to the login page 
            //the user will see the input fields with the values he/she has entered
            SessionManager::setSessionVariable('input_fiels', $_POST);
            //sets the error message in the session variable
            SessionManager::setSessionVariable('error_message', 'Please check your email or passwor and try to log in.');
          
            header("Location: " . BASE_URL . "/login");
            exit();
        }
    }


    public function signupView() {
        try {
            include 'views/signup.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function accountView() {
        try {
            if (SessionManager::isLoggedIn()) {
                include 'views/account.php';
            } else {
                SessionManager::setSessionVariable('error_message', 'Please login to view your account.');  
                header("Location: " . BASE_URL . "/login");
                exit(); 
            }
            } catch (\PDOException $ex) {
                error_log('PDO Exception: ' . $ex->getMessage());
            }
    }
    
}
