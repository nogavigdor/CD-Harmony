<?php

namespace models;

use \DataAccess\DBConnector;

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
            $query->bindParam(':first_name', $first_name, \PDO::PARAM_STR);  // Use the correct property name
            $query->bindParam(':last_name', $last_name , \PDO::PARAM_STR);    // Use the correct property name
            $query->bindParam(':email', $email, \PDO::PARAM_STR);
            $query->bindParam(':hashed_password', $hashed_password, \PDO::PARAM_STR);
            $query->bindParam(':role', $role, PDO::PARAM_INT);
            $query->execute();
         }
         catch (\PDOException $ex) {
         //   die("Connection failed: " . $e->getMessage());
            $message = "User could not be created.";
            $message .= "<br />" . $ex->getMessage();
            echo $message;
         }
    }   


} 