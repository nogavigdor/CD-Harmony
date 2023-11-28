<?php
namespace Controllers;


class LoginController 
{
    public function __construct() {
    }

    public function loginView()
    {
        try {
            include_once 'views/login.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }




}