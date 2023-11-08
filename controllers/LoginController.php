<?php
namespace controllers;

//use models\ContactModel;



class LoginController 
{
    public function __construct() {
    }

    public function loginView()
    {
        try {
           // $contacttModel = new ContactModel(); 
          //  $temp = $contactModel->getSomething($toBeContinued); 
    
        //    print_r($products);
            // Load the view to display the products
            include_once 'views/login.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function loginCheck()
    {
        try {
           // $logintModel = new LoginModel(); 
          //  $temp = $LoginModel->getSomething($toBeContinued); 
    
        //    print_r($products);
            // Load the view to display the products
            include 'views/login.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }



}