<?php

namespace DataAccess;

require(__DIR__ . '/db_constants.php');


class DBConnector
{
    // Static variable to hold the single instance
    private static $instance; 
    // Instance variable to hold the database connection
    private $link; 

    private function __construct()
    {
        // Private constructor to prevent external instantiation
        $this->link = new \PDO(
            'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false
            )
        );
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            // If the instance doesn't exist, create a new one
            self::$instance = new self();
        }
        // Return the single instance
        return self::$instance; 
    }

    public function connectToDB()
    {
        // Return the database connection
        return $this->link; 
    }

    public function closeConnection()
    {
         // Set the database connection property to null
        $this->link = null;
    }
}
