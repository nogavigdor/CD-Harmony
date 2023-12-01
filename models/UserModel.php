<?php 
namespace Models;

use \DataAccess\DBConnector;


use PDO; 

    class UserModel 
    {
        private $dbConnector; 

        public function __construct()
        {
            $this->dbConnector = DBConnector::getInstance(); 
        }
        //customer role is defined as 3 in the database and that should be the defailt value
        public function setAccount($email, $password, $first_name="", $last_name="", $role=3) {
            try {
                $first_name="";
                $last_name="";
                $iterations = ['cost' => 15];
                $hashed_password = password_hash($password, PASSWORD_BCRYPT, $iterations);
                $creation_date = date('Y-m-d H:i:s');
                $dbInstance = $this->dbConnector;
                $db=$dbInstance->connectToDB();
                
                // Fetching user data from the database based on the email
                $sql = "SELECT * FROM users WHERE email = :email";
                $query = $db->prepare($sql);
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
                INSERT INTO users (first_name, last_name, email, user_password, creation_date, role_id) VALUES (:first_name,:last_name, :email, :hashed_password, :creation_date, :role)
                ';
                $query = $db->prepare($sql);
                $query->bindParam(':first_name', $first_name, \PDO::PARAM_STR);  
                $query->bindParam(':last_name', $last_name , \PDO::PARAM_STR);    
                $query->bindParam(':email', $email, \PDO::PARAM_STR);
                $query->bindParam(':hashed_password', $hashed_password, \PDO::PARAM_STR);
                $query->bindParam(':creation_date', $creation_date, \PDO::PARAM_STR);
                $query->bindParam(':role', $role, PDO::PARAM_INT);
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
        //checks if the login details mataches any of the existing users
        public function getAccount($email, $password) {
            try {   
                $dbInstance = $this->dbConnector;
                $db = $dbInstance->connectToDB();
        
                // Fetching user data from the database based on the email
                $sql = "SELECT * FROM users WHERE email = :email";
                $query = $db->prepare($sql);
                $query->bindParam(':email', $email);
                $query->execute();
        
                // Fetching the user data
                $user = $query->fetch(\PDO::FETCH_ASSOC);
        
                // If a user is found, passwords are compared
                if ($user) {
                    // Verify the password
                    if (password_verify($password, $user['user_password'])) {
                        // Returns user data if the password is correct
                        return $user;
                    }
                }
        
                // Return false if no user is found or the password is incorrect
                return false;
            } catch (\PDOException $ex) {
                // Handle PDOException
                error_log('PDOException in getAccount: ' . $ex->getMessage());
        
    
        }

    } 
    }