<?php
namespace Controllers;
use Services\SessionManager;
use Models\UserModel;
SessionManager::startSession();
class AdminController
{
  
    public function adminView()
    {
           // Check if the user is logged in and if it has an admin role
           if (SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role'] == 1) {
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
          if (SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role'] == 1) {
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
        $email = trim(htmlspecialchars($_POST['email']));
        //no need to trim or htmlspecialchars password because it is hashed
        $password = $_POST['password'];
        //retrieves the user data from the database
        $userModel = new UserModel();
        $user = $userModel->getAccount($email, $password);
    
        //if user is found and if the user has an admin role       
        if ($user && $user['role_id'] == 1) {
            $userData = array(
                'logged_in' => true,
                'id' => $user['user_id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'first_name' => isset($user['first_name']) ? $user['first_name'] : "",
            );

            //sets the user data in the session variable
             SessionManager::setSessionVariable('user', $userData);    
            $success_message = "Hi " . $user['first_name'];
            
           
            //sets the success message in the session variable
            SessionManager::setSessionVariable('success_message', $success_message);  
               

            // Redirect to the admin page
            header('Location:'. BASE_URL. '/admin');
            exit();
        } else {
            $errorMessage = 'You are not authororized to to access the page or you have entered invalid input.';
            SessionManager::setSessionVariable('error_message', $errorMessage);
            header('Location:'. BASE_URL. '/admin-login');
            exit();
      
        }
    }

   
}
