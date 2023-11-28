<?php
namespace Controllers;

class MainViewController 
{
   

    public function __construct() {
        
    }

    public function showMainView()
    {
        try {   
            // Load the view to display the products
            include_once 'views/MainView.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
  

 
}
