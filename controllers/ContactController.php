<?php
namespace controllers;

//use models\ContactModel;

class ContactController 
{
    public function __construct() {
    }

    public function handleInput()
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


}
