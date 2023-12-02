<?php
namespace Controllers;
use Services\SessionManager;
use Models\UsersModel;

class AdminController
{
  
    public function adminView()
    {
           // Check if the user is logged in and if it has an admin role
           if (SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role_ud'] == 1) {
            // User is an admin, show the admin page
            include 'views/admin/adminView.php';
            } else {
            // Redirect to the login page
            header('Location:'. BASE_URL. '/admin-login');
            exit();
            }
   }

   public function adminLoginView()
   {
          // Check if the user is logged in and if it has an admin role
          if (SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role_ud'] == 1) {
           // User is an admin, redirect to the admin page
             header('Location:'. BASE_URL. '/admin');
             exit();
           } else {
           // user is not logged in ad admin 
           include 'views/admin/admin-login.php';
           }
  }



    public function adminLogin()
    {
        // Validate login credentials 
        $email = trim(htmlespecialchars($_POST['email']));
        //no need to trim or htmlspecialchars password because it is hashed
        $password = $_POST['password'];

        // Check the database for the user with the provided credentials
        $user = new UserModel();
        $user = $user->getAccount($email, $password);
        
        if ($user && $user['role_id'] == 1) {
            // Valid admin login, set session variables
            SessionManager::setSessionVariable('user', $user);

            // Redirect to the admin page
            header('Location:'. BASE_URL. '/admin');
            exit();
        } else {
            // Invalid login, redirect back to the login page
            echo 'Invalid login';
        }
    }

   
}
