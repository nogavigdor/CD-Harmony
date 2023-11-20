<?php


//implementation of a Singleton Pateern 

namespace DataAccess\DB;

require(__DIR__ . '/db_constants.php');

class DBConnector
{
    // Static instance variable to hold the single instance
    private $link;
    private static $instance;  
z
    private function __construct()
    {
        // Private constructor to prevent instantiation outside the class
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connectToDB()
    {
        if (!$this->link) {
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

        return $this->link;
    }

    public function closeConnection()
    {
        $this->link = null;
    }
}

/*  old class 

class DBConnector
{
    private $link;

    function connectToDB()
    {
        if (!$this->link) {
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

        return $this->link;
    }

    function closeConnection()
    {
        $this->link = null;
    }
}
*/


