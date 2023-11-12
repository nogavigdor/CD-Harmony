<?php

namespace controllers;

use models\UserModel;
use Services\Validator;
use Services\SessionManager;

class UserController {
    
    private $sessionManager;

    public function __construct() {
        $this->sessionManager = new SessionManager();
    }
    
    public function createAccount() {
        //removes any while spaces from the begining and the end of the string
       // $first_name =trim( $_POST['first_name']);
       // $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $validator = new Validator();
        
        //checks if both the psassword and email are valid for account creation
        //the method will return null in case the password is valid
        if ($errType=$validator->validatePassword($password)) {
            $this->sessionManager->setSessionVariable('error_message',$errType);
        if ($errType=$validator->validateEmail($email))           
             $this->sessionManager->setSessionVariable('error_message',$errType);

        //null was returned on both email and password validations and therefor they are valid
            $userModel=new UserModel();
            $userModel->setAccount($email, $password);
        }

        //redirects to the signup page after a successfull user creation
        header("Location: " . BASE_URL . "/login");
        exit();
    }

    public function authenticateUser() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $user = $userModel->getAccount($email, $password);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->sessionManager->setSessionVariable('user_id', $user['id']);
                $this->sessionManager->setSessionVariable('user_email', $user['email']);
                echo "hurray";

                header("Location: " . BASE_URL . "/");
                exit();
            } else {
                $this->handleLoginError('Incorrect password. Please try again.');
            }
        } else {
            $this->handleLoginError('Email not found. Please try again.');
        }
    }

    private function handleLoginError($errorMessage) {
        $this->sessionManager->setSessionVariable('error_message', $errorMessage);
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
