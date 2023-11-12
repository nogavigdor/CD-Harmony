<?php

namespace controllers;

use models\UserModel;
use Services\Validator;

class UserController {
    
   
    public function __construct() {
   
    }
    
    public function createAccount() {
        //removes any while spaces from the begining and the end of the string
       // $first_name =trim( $_POST['first_name']);
       // $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $validator = new Validator();
        
        //checks if the psassword is valid for account creation
        if ($validator->validatePasswordForSignup($password)) {
           $role=1; //in the database a customer role is defiined as 1

            $userModel=new UserModel();
            $userModel->setAccount($email, $password);
        } else {
            // Handle invalid password for registration
            echo 'This message is form the UserController : Your password is not elgiable. pleaese try again.';
        }
    }

    public function authenticateUser($userInputPassword) {
        // ... user authentication logic ...
        $userModel = new UserModel();

        if ($this->validator->validatePasswordForLogin($userInputPassword)) {
            // Password is valid for user authentication
            // Proceed with login logic
        } else {
            // Handle invalid password for login
        }
    }

    public function signupView()
    {
        try {
            include 'views/signup.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
