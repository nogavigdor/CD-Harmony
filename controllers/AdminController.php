<?php
namespace Controllers;

use Services\SessionManager;
use Models\UserModel;

class AdminController
{
    public function __construct()
    {
        $session = new SessionManager();
        $session->startSession();
    }
  
    public function adminView()
    {
        // Check if the user is logged in and if it has an admin role
        if (self::authorizeAdmin()) {
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
        // Check if the user is already logged in as admin
        if (self::authorizeAdmin()) {
            // User is already logged in as admin, redirect to the admin page
            header('Location:'. BASE_URL. '/admin');
            exit();
        } else {
            // User is not logged in as admin, show the admin login form
            include 'views/admin/admin-login.php';
        }
    }

    public function adminLogin()
    {
        // Validate the CSRF token
        if (!SessionManager::validateCSRFToken($_POST['csrf_token'])) {
            exit("CSRF token validation failed.");
        }
        
        // Retrieve login credentials from the form
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
    
        // Check if email and password are provided
        if (empty($email) || empty($password)) {
            $errorMessage = 'Please enter both email and password.';
            SessionManager::setSessionVariable('alert_message', $errorMessage);
            header('Location:'. BASE_URL. '/admin-login');
            exit();
        }
    
        // Fetch user data from the database
        $userModel = new UserModel();
        $user = $userModel->getAccount($email, 1); // Assuming admin role ID is 1
    
        // Verify user credentials
        if ($user && password_verify($password, $user['user_password'])) {
            // Set user data in session
            // Redirect to the admin page
            header('Location:'. BASE_URL. '/admin');
            exit();
        } else {
            // Invalid credentials, show error message and redirect to login page
            $errorMessage = 'Invalid email or password.';
            SessionManager::setSessionVariable('alert_message', $errorMessage);
            header('Location:'. BASE_URL. '/admin-login');
            exit();
        }
    }
    
    // Checks if the user is logged in as admin
    public static function authorizeAdmin() {
        $userData = SessionManager::getSessionVariable('user');
        return ($userData && $userData['role'] == 1);
    }
}
