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

        public function getAllProducts(){
            try{
                $dbInstance = $this->dbConnector;
                $db=$dbInstance->connectToDB();
                $sql='
                SELECT
                p.product_id,
                pv.product_variant_id,
                p.title as product_title,
                a.title AS artist_title,
                t.title AS tag_title,
                ip.image_name,
                ip.image_path,
                SUM(CASE WHEN pv.condition_id = 1 THEN pv.quantity_in_stock ELSE 0 END) AS new_quantity,
                SUM(CASE WHEN pv.condition_id = 2 THEN pv.quantity_in_stock ELSE 0 END) AS used_quantity,
                MAX(CASE WHEN pv.condition_id = 1 THEN pv.price END) AS new_price,
                MAX(CASE WHEN pv.condition_id = 2 THEN pv.price END) AS used_price
                FROM
                    products p
                INNER JOIN products_tags pt ON pt.product_id = p.product_id
                INNER JOIN tags t ON t.tag_id = pt.tag_id
                INNER JOIN cds c ON c.product_id = p.product_id
                INNER JOIN artists a ON a.artist_id = c.artist_id
                LEFT JOIN product_variants pv ON pv.product_id = p.product_id
                INNER JOIN images_for_products ip ON ip.product_id = p.product_id
                GROUP BY p.product_id
            
                ';
                $query = $db->prepare($sql);
                $query->execute();
                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (\PDOException $ex) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        //Get product cds by tag
        public function getProductsByTag($tag) {
            try {
            
                $dbInstance = $this->dbConnector;
                $db=$dbInstance->connectToDB();
                $sql='
                SELECT
                p.product_id,
                pv.product_variant_id,
                p.title as product_title,
                a.title AS artist_title,
                t.title AS tag_title,
                ip.image_name,
                ip.image_path,
                SUM(CASE WHEN pv.condition_id = 1 THEN pv.quantity_in_stock ELSE 0 END) AS new_quantity,
                SUM(CASE WHEN pv.condition_id = 2 THEN pv.quantity_in_stock ELSE 0 END) AS used_quantity,
                MAX(CASE WHEN pv.condition_id = 1 THEN pv.price END) AS new_price,
                MAX(CASE WHEN pv.condition_id = 2 THEN pv.price END) AS used_price
                FROM
                    products p
                INNER JOIN products_tags pt ON pt.product_id = p.product_id
                INNER JOIN tags t ON t.tag_id = pt.tag_id
                INNER JOIN cds c ON c.product_id = p.product_id
                INNER JOIN artists a ON a.artist_id = c.artist_id
                LEFT JOIN product_variants pv ON pv.product_id = p.product_id
                INNER JOIN images_for_products ip ON ip.product_id = p.product_id
                WHERE t.title LIKE :tag

                GROUP BY p.product_id
                LIMIT 10
                ';
                $query = $db->prepare($sql);
                $query->bindParam(':tag', $tag, PDO::PARAM_STR);
                $query->execute();
            //var_dump($query->queryString);
            //var_dump($tag);
                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (\PDOException $ex) {
                die("Connection failed: " . $e->getMessage());
            }  
                
            
        }


        //get recent release of cds
        public function getRecentReleases() {
            // Implement the logic to fetch recent releases here
            // For example:
            try {
                $dbInstance = $this->dbConnector;
                $db=$dbInstance->connectToDB();
                $sql='
                SELECT
                p.product_id,
                pv.product_variant_id,
                c.release_date,
                p.title as product_title,
                a.title AS artist_title,
                t.title AS tag_title,
                ip.image_name,
                ip.image_path,
                SUM(CASE WHEN pv.condition_id = 1 THEN pv.quantity_in_stock ELSE 0 END) AS new_quantity,
                SUM(CASE WHEN pv.condition_id = 2 THEN pv.quantity_in_stock ELSE 0 END) AS used_quantity,
                MAX(CASE WHEN pv.condition_id = 1 THEN pv.price END) AS new_price,
                MAX(CASE WHEN pv.condition_id = 2 THEN pv.price END) AS used_price
                FROM
                    products p
                INNER JOIN products_tags pt ON pt.product_id = p.product_id
                INNER JOIN tags t ON t.tag_id = pt.tag_id
                INNER JOIN cds c ON c.product_id = p.product_id
                INNER JOIN artists a ON a.artist_id = c.artist_id
                LEFT JOIN product_variants pv ON pv.product_id = p.product_id
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
            pv.product_variant_id,
            con.title AS condition_title,
            p.title as product_title,
            p.product_description,
            a.title AS artist_title,
            c.release_date,
            t.title AS tag_title,
            ip.image_name,
            ip.image_path,
            pv.quantity_in_stock,
            pv.price
            FROM
            products p
            INNER JOIN products_tags pt ON pt.product_id = p.product_id
            INNER JOIN tags t ON t.tag_id = pt.tag_id
            INNER JOIN cds c ON c.product_id = p.product_id
            INNER JOIN artists a ON a.artist_id = c.artist_id
            LEFT JOIN product_variants pv ON pv.product_id = p.product_id
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

    //Show all products in the admin page
    public function getProductsList() {
        try {
            
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $sql = '
            SELECT
            pv.product_variant_id,
            p.product_id,
            pv.condition_id,
            p.title AS product_title,
            p.product_description AS description,
            a.title AS artist_title,
            t.title AS tag_title,
            ip.image_name,
            ip.image_path,
            con.title AS condition_title,        
            pv.quantity_in_stock 
        FROM
            products p
        INNER JOIN product_variants pv ON p.product_id = pv.product_id
        INNER JOIN products_tags pt ON p.product_id = pt.product_id
        INNER JOIN tags t ON pt.tag_id = t.tag_id
        LEFT JOIN cds c ON p.product_id = c.product_id
        LEFT JOIN artists a ON c.artist_id = a.artist_id
        LEFT JOIN images_for_products ip ON p.product_id = ip.product_id
        INNER JOIN conditions con ON pv.condition_id = con.condition_id
        GROUP BY pv.product_variant_id
             ';
            $query = $db->prepare($sql);
            echo $query->queryString;

            $query->execute();
        //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $ex->getMessage());
        }  
            
    }

       //Show all products in the admin page
       public function updateProduct() {
        try {
            
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $sql = '
            SELECT
            pv.product_variant_id,
            p.product_id,
            pv.condition_id,
            p.title AS product_title,
            p.product_description AS description,
            a.title AS artist_title,
            t.title AS tag_title,
            ip.image_name,
            ip.image_path,
            con.title AS condition_title,        
            pv.quantity_in_stock 
        FROM
            products p
        INNER JOIN product_variants pv ON p.product_id = pv.product_id
        INNER JOIN products_tags pt ON p.product_id = pt.product_id
        INNER JOIN tags t ON pt.tag_id = t.tag_id
        LEFT JOIN cds c ON p.product_id = c.product_id
        LEFT JOIN artists a ON c.artist_id = a.artist_id
        LEFT JOIN images_for_products ip ON p.product_id = ip.product_id
        INNER JOIN conditions con ON pv.condition_id = con.condition_id
        GROUP BY pv.product_variant_id
             ';
            $query = $db->prepare($sql);
            echo $query->queryString;

            $query->execute();
        //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $ex->getMessage());
        }  
            
    }







    }
