<?php
namespace Controllers;
use Services\SessionManager;
use Models\UserModel;
use Models\ProductModel;
use Models\ArticleModel;
use Models\SpecialOfferModel;
use Models\CompanyModel;
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
          // Check if the user is logged in and if it has an admin role
          if (self::authorizeAdmin()) {
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
        if (isset($_POST['password']))
            $email = trim(htmlspecialchars($_POST['email']));
        if (isset($_POST['password']))
        //no need to trim or htmlspecialchars password because it is hashed
            $password = trim($_POST['password']);

        //sets the role to 1 (admin)
        $role = 1;
        //retrieves the user data from the database
        $userModel = new UserModel();
        $user = $userModel->getAccount($email, $role);
    
        //if user is found and if the user has an admin role       
        if (!empty($user) && (password_verify($password, $user['user_password']))) {
            $userData = array(
                'logged_in' => true,
                'id' => $user['user_id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'first_name' => isset($user['first_name']) ? $user['first_name'] : "",
            );

               //sets the user data in the session variable
               SessionManager::setSessionVariable('user', $userData);    
               $successMessage = "Hi " . $user['first_name']. ' you are now logged in as admin';

                          
           
            //sets the success message in the session variable
            SessionManager::setSessionVariable('success_message', $successMessage);  
              // Redirect to the admin page
              header('Location:'. BASE_URL. '/admin');
              exit();         
        } else {
            $errorMessage = 'Please enter valid credentials.';
            SessionManager::setSessionVariable('error_message', $errorMessage);
            header('Location:'. BASE_URL. '/admin-login');
            exit();
      
        }
    }

    
   

    public function getProductDetails()
    {
        // Check if the user is logged in and if it has an admin role
        if(self::authorizeAdmin()) {
            // User is an admin, show the products page
            include 'views/admin/product-details.php';
            } else {
            // Redirect to the login page
            header('Location:'. BASE_URL. '/admin-login');
            exit();
            }
    }
    //checks if the user is logged in and if it has an admin role
    public static function authorizeAdmin() {
        if (SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

   
}
