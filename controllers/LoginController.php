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


      //returns true if the user is logged in
      public static function isLoggedIn()
      {
          if (!isset($_SESSION['user']['logged_in'])) {
              return false;
          }
          return SessionManager::getSessionVariable('user')['logged_in'];
      }
      //logs out the user
      public static function logout()
      {
          // Check if the user is an admin or not
          if (self::isAdmin()) {
            SessionManager::unsetSessionVariable('user');
              header("Location: " . BASE_URL . "/admin-login");
              SessionManager::setSessionVariable('success_message',
               'You have been logged out successfully.');
          }
          // If the user is not an admin, log out as a normal user
          else {
              SessionManager::unsetSessionVariable('user');
              header("Location: " . BASE_URL . "/login");
              SessionManager::setSessionVariable('success_message',
               'You have been logged out successfully. See you soon!');
          }
      }
  
      //returns true is the user is customer
      public static function isCustomer()
      {
          if (!isset($_SESSION['user']['role'])) {
              return false;
          }
          return SessionManager::getSessionVariable('user')['role'] == 3;
      }
  
      //returns true if the user is an admin
      public static function isAdmin()
      {
       
  
          
          if (!isset($_SESSION['user']['role'])) {
              return false;
          }
          
          
          return SessionManager::getSessionVariable('user')['role'] == 1;
      }

      public function getLoggedInUserId()
      {
          return SessionManager::getSessionVariable('user')['id'] ?? null;
      }
  


}