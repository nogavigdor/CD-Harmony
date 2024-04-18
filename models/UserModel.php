<?php 
namespace Models;

use \DataAccess\DBConnector;
use Services\SessionManager;


use PDO; 

    class UserModel 
    {
        private $db; 

        public function __construct()
        {
            $this->db = DBConnector::getInstance()->connectToDB();
        }
        //customer role is defined as 3 in the database and that should be the defailt value
        public function setAccount($email, $password, $first_name="", $last_name="", $role = 3) {
            try {
                $first_name="";
                $last_name="";
                $iterations = ['cost' => 15];
                $hashed_password = password_hash($password, PASSWORD_BCRYPT, $iterations);
                $creation_date = date('Y-m-d H:i:s');
                
                
                // Fetching user data from the database based on the email
                $sql = "SELECT * FROM users WHERE email = :email";
                $query = $this->db->prepare($sql);
                $query->bindParam(':email', $email, \PDO::PARAM_STR);
                $query->execute();
                $user = $query->fetch(\PDO::FETCH_ASSOC);

                // If email was found user already exists, therefore redirect to signup page
                if ($user){
                   //
                    return false;
                }
                else
                 {

                //email/user doesn't exist, therefor Inserting user data into the database
                $sql = '
                INSERT INTO users (first_name, last_name, email, user_password, creation_date, role_id) VALUES (:first_name,:last_name, :email, :hashed_password, :creation_date, :role_id)
                ';
                $query = $this->db->prepare($sql);
                $query->bindParam(':first_name', $first_name, \PDO::PARAM_STR);  
                $query->bindParam(':last_name', $last_name , \PDO::PARAM_STR);    
                $query->bindParam(':email', $email, \PDO::PARAM_STR);
                $query->bindParam(':hashed_password', $hashed_password, \PDO::PARAM_STR);
                $query->bindParam(':creation_date', $creation_date, \PDO::PARAM_STR);
                $query->bindParam(':role_id', $role, PDO::PARAM_INT);
                $query->execute();
                return true;
                }

               
            }
            catch (\PDOException $ex) {
            // die("Connection failed: " . $e->getMessage());
                SessionManager::setSessionVariable('error_message', 'User could not be created. Please try again. ' . $ex->getMessage());
                header('Location: ' . BASE_URL).'/signup';
                exit();
            }
        }   
        //checks if the login details mataches any of the existing users (customers)
        public function getAccount($email, $role) {
            try {   
          
                // Fetching user data from the database based on the email
                $sql = 'SELECT * FROM users WHERE email = :email AND role_id = :role_id';
                
                $query = $this->db->prepare($sql);
                $query->bindParam(':email', $email);
                $query->bindParam(':role_id', $role);
                $query->execute();
        
                // Fetching the user data
                $user = $query->fetch(\PDO::FETCH_ASSOC);
        
                // return true if the user was found and false if not
                return $user;
                
            } catch (\PDOException $ex) {
                // Handle PDOException
                error_log('PDOException in getAccount: ' . $ex->getMessage());
        
    
        }

    } 

    public function hasMadePurchace($user_id){
        try {    
            // Fetching user data from the database based on user_id
            $sql = 'SELECT * FROM orders WHERE user_id = :user_id';
            
            $query = $this->db->prepare($sql);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
    
            // Fetching the user data
            $user = $query->fetch(\PDO::FETCH_ASSOC);
    
             // return true if the user was found and false if not
            return $user ? true : false;
            
        } catch (\PDOException $e) {
            // Handle PDOException
            error_log('PDOException in getAccount: ' . $e->getMessage());
      }
    }

    //get all admin or editor users
    public function getAdminANDEditorUsers(){
        try {    
            // Fetching user data from the database based on user_id
            $sql = 'SELECT * FROM users WHERE role_id = 1 OR role_id = 2';
            
            $query = $this->db->prepare($sql);
            $query->execute();
    
            // Fetching the user data
            $users = $query->fetchAll(\PDO::FETCH_ASSOC);
    
             // return true if the user was found and false if not
            return $users;
            
        } catch (\PDOException $e) {
            // Handle PDOException
            error_log('PDOException in getAccount: ' . $e->getMessage());
      }
    }

}