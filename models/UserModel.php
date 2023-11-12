<?php

namespace models;

use \DataAccess\DBConnector;
use Services\SessionManager; 

use PDO; 

class UserModel 
{
    private $dbConnector; 

    public function __construct()
    {
        $this->dbConnector = DBConnector::getInstance(); 
    }
    //customer role is defined as 1 in the database and that should be the defailt value
    public function setAccount($email, $password, $role=1) {
         try {
            $first_name="";
            $last_name="";
            $iterations = ['cost' => 15];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, $iterations);
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $sql = '
            INSERT INTO users (first_name, last_name, email, user_password, role_id) VALUES (:first_name,:last_name, :email, :hashed_password, :role)
            ';
            $query = $db->prepare($sql);
            $query->bindParam(':first_name', $first_name, \PDO::PARAM_STR);  
            $query->bindParam(':last_name', $last_name , \PDO::PARAM_STR);    
            $query->bindParam(':email', $email, \PDO::PARAM_STR);
            $query->bindParam(':hashed_password', $hashed_password, \PDO::PARAM_STR);
            $query->bindParam(':role', $role, PDO::PARAM_INT);
            $query->execute();
         }
         catch (\PDOException $ex) {
         // die("Connection failed: " . $e->getMessage());
            SessionManager::setSessionVariable('error_message', 'User could not be created. ' . $ex->getMessage());
            header("Location: signup.php");
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
                if (password_verify($password, $user['password'])) {
                    // Returns user data if the password is correct
                    return $user;
                }
            }
    
            // Return null if no user is found or the password is incorrect
            return null;
        } catch (\PDOException $ex) {
            // Handle PDOException
            error_log('PDOException in getAccount: ' . $ex->getMessage());
      
   
    }

} 