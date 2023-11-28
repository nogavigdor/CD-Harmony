<?php

namespace Models;

use \DataAccess\DBConnector;

use PDO; 

class ProductModel 
{
    private $dbConnector; 

    public function __construct()
    {
        $this->dbConnector = DBConnector::getInstance(); 
    }

    public function getProductsByTag($tag) {
        try {
         
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $sql='
            SELECT
            p.product_id,
            p.title as product_title,
            a.title AS artist_title,
            t.title AS tag_title,
            ip.image_name,
            ip.image_path,
            SUM(CASE WHEN pc.condition_id = 1 THEN pc.quantity_in_stock ELSE 0 END) AS new_quantity,
            SUM(CASE WHEN pc.condition_id = 2 THEN pc.quantity_in_stock ELSE 0 END) AS old_quantity,
            MAX(CASE WHEN pc.condition_id = 1 THEN pc.price END) AS new_price,
            MAX(CASE WHEN pc.condition_id = 2 THEN pc.price END) AS old_price
            FROM
                products p
            INNER JOIN products_tags pt ON pt.product_id = p.product_id
            INNER JOIN tags t ON t.tag_id = pt.tag_id
            INNER JOIN cds c ON c.product_id = p.product_id
            INNER JOIN artists a ON a.artist_id = c.artist_id
            LEFT JOIN products_conditions pc ON pc.product_id = p.product_id
            INNER JOIN images_for_products ip ON ip.product_id = p.product_id
            WHERE t.title LIKE :tag

            GROUP BY p.product_id
            LIMIT 10
            ';
            $query = $db->prepare($sql);
            $query->bindParam(':tag', $tag, PDO::PARAM_STR);
            $query->execute();
         //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $e->getMessage());
        }  
            
         
    }



    public function getRecentReleases() {
        // Implement the logic to fetch recent releases here
        // For example:
        try {
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $sql='
            SELECT
            p.product_id,
            c.release_date,
            p.title as product_title,
            a.title AS artist_title,
            t.title AS tag_title,
            ip.image_name,
            ip.image_path,
            SUM(CASE WHEN pc.condition_id = 1 THEN pc.quantity_in_stock ELSE 0 END) AS new_quantity,
            SUM(CASE WHEN pc.condition_id = 2 THEN pc.quantity_in_stock ELSE 0 END) AS old_quantity,
            MAX(CASE WHEN pc.condition_id = 1 THEN pc.price END) AS new_price,
            MAX(CASE WHEN pc.condition_id = 2 THEN pc.price END) AS old_price
            FROM
                products p
            INNER JOIN products_tags pt ON pt.product_id = p.product_id
            INNER JOIN tags t ON t.tag_id = pt.tag_id
            INNER JOIN cds c ON c.product_id = p.product_id
            INNER JOIN artists a ON a.artist_id = c.artist_id
            LEFT JOIN products_conditions pc ON pc.product_id = p.product_id
            INNER JOIN images_for_products ip ON ip.product_id = p.product_id
   
            WHERE release_date BETWEEN \'2023-01-21\' AND CURDATE()

            GROUP BY p.product_id
            LIMIT 10

            ';
            $query = $db->prepare($sql);
     
            $query->execute();
         //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $e->getMessage());
        } 
    }

	function getProductDetails($id)
{
    try {
        $dbInstance = $this->dbConnector;
        $db=$dbInstance->connectToDB();
        $sql = '
        SELECT
        p.product_id,
        p.title as product_title,
        p.product_description,
        a.title AS artist_title,
        c.release_date,
        t.title AS tag_title,
        ip.image_name,
        ip.image_path,
        SUM(CASE WHEN pc.condition_id = 1 THEN pc.quantity_in_stock ELSE 0 END) AS new_quantity,
        SUM(CASE WHEN pc.condition_id = 2 THEN pc.quantity_in_stock ELSE 0 END) AS old_quantity,
        MAX(CASE WHEN pc.condition_id = 1 THEN pc.price END) AS new_price,
        MAX(CASE WHEN pc.condition_id = 2 THEN pc.price END) AS old_price
        FROM
        products p
        INNER JOIN products_tags pt ON pt.product_id = p.product_id
        INNER JOIN tags t ON t.tag_id = pt.tag_id
        INNER JOIN cds c ON c.product_id = p.product_id
        INNER JOIN artists a ON a.artist_id = c.artist_id
        LEFT JOIN products_conditions pc ON pc.product_id = p.product_id
        INNER JOIN images_for_products ip ON ip.product_id = p.product_id
        WHERE p.product_id = :id
        ';
        $query = $db->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(\PDO::FETCH_OBJ);

        return $result;
    } catch (\PDOException $ex) {
        die("Connection failed: " . $e->getMessage());
    } 
}





}
