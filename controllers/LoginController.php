<?php
namespace Controllers;
use Services\SessionManager;


class LoginController 
{
    private $session;
    public function __construct() {
        $session = new SessionManager();
        $session->startSession();
        $this->session = $session;
    }

    public function loginView()
    {
        try {
            include_once 'views/login.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


    public function logout()
    {
        try {
            $this->session::logout();
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


}