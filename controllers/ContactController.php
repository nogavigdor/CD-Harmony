<?php
namespace controllers;

//use models\ContactModel;

class ContactController 
{
    public function __construct() {
    }

    public function contactView()
    {
        try {
           // $contacttModel = new ContactModel(); 
          //  $temp = $contactModel->getSomething($toBeContinued); 
    
        //    print_r($products);
            // Load the view to display the products
            include_once 'views/contact.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function contactInput($toBeContinued)
    {
        try {
            $contactModel = new ContactModel(); 
            $contact = $contactModel->handleInput($toBeContinued); 
    
          // var_dump($products);
        //    print_r($products);
            // Load the view to display the products
            include 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


}
