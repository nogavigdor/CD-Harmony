<?php
namespace Controllers;

class SignupController 
{
    public function __construct() {
    }

    public function signupView()
    {
        try {
           // $contacttModel = new ContactModel(); 
          //  $temp = $contactModel->getSomething($toBeContinued); 
    
        //    print_r($products);
            // Load the view to display the products
            include 'views/signup.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function signupCheck()
    {
        try {
           // $logintModel = new LoginModel(); 
          //  $temp = $LoginModel->getSomething($toBeContinued); 
    
        //    print_r($products);
            // Load the view to display the products
            include 'views/signup.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }



}

