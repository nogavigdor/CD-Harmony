<?php
namespace controllers;

use Services\SessionManager;
use models\UsersModel;

class AdminController
{
  
    public function adminView()
    {
           // Check if the user is logged in and is an admin
           if (SessionManager::getSessionVariable('user_id') && SessionManager::getSessionVariable('role') == 3) {
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
        // Validate login credentials (you may want to sanitize and validate inputs)
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check the database for the user with the provided credentials
        $user = UserModel::getUserByUsernameAndPassword($username, $password);

        if ($user && $user['role'] == 3) {
            // Valid admin login, set session variables
            SessionManager::setSessionVariable('user_id', $user['id']);
            SessionManager::setSessionVariable('role', $user['role']);

            // Redirect to the admin page
            header('Location: /admin');
            exit();
        } else {
            // Invalid login, redirect back to the login page
            header('Location: /admin/login');
            exit();
        }
    }

   
}
