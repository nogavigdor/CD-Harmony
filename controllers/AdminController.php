<?php
namespace Controllers;

use Services\SessionManager;
use Models\UsersModel;

class AdminController
{
  
    public function adminView()
    {
           // Check if the user is logged in and is an admin
           if (SessionManager::getSessionVariable('user_id') && SessionManager::getSessionVariable('role') == 1) {
            // User is an admin, show the admin page
            include 'views/admin/index.php';
            } else {
            // Redirect to the login page
            header('Location: /admin/login');
            exit();
    }
   }


    public function login()
    {
        // Validate login credentials 
        $email = trim(htmlespecialchars($_POST['email']));
        //no need to trim or htmlspecialchars password because it is hashed
        $password = $_POST['password'];

        // Check the database for the user with the provided credentials
        $user = UserModel::getUserByUsernameAndPassword($username, $password);

        if ($user && $user['role_id'] == 3) {
            // Valid admin login, set session variables
            SessionManager::setSessionVariable('user[user_id]', $user['user_id']);
            SessionManager::setSessionVariable('user[role]', $user['user_role']);

            // Redirect to the admin page
            header('Location: /admin');
            exit();``
        } else {
            // Invalid login, redirect back to the login page
            header('Location: /admin/login');
            exit();
        }
    }

   
}
