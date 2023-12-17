<?php
namespace Models;

    use \DataAccess\DBConnector;

    use PDO; 

    class ProductModel 
    {
        private $db; 

        public function __construct()
        {
            $this->db = DBConnector::getInstance()->connectToDB();
        }

        public function getAllProducts(){
            try{
                $sql='     
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
                product_variants pv
                INNER JOIN products p ON p.product_id = pv.product_id
                INNER JOIN products_tags pt ON p.product_id = pt.product_id
                INNER JOIN tags t ON pt.tag_id = t.tag_id
                LEFT JOIN cds c ON p.product_id = c.product_id
                LEFT JOIN artists a ON c.artist_id = a.artist_id
                LEFT JOIN images_for_products ip ON p.product_id = ip.product_id
                INNER JOIN conditions con ON pv.condition_id = con.condition_id
                GROUP BY p.product_id
                ';
                $query = $this->db->prepare($sql);
                $query->execute();
                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        //Get product cds by tag
        public function getProductsByTag($tag) {
            try {
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
                $query = $this->db->prepare($sql);
                $query->bindParam(':tag', $tag, PDO::PARAM_STR);
                $query->execute();
            //var_dump($query->queryString);
            //var_dump($tag);
                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }  
                
            
        }


        //get recent release of cds
        public function getRecentReleases() {
            // Implement the logic to fetch recent releases here
            // For example:
            try {
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
                $query = $this->db->prepare($sql);
        
                $query->execute();
            //s   var_dump($query->queryString);
            //var_dump($tag);
                return $query->fetchAll(PDO::FETCH_OBJ);
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            } 
        }

        //Get product details by product id
        function getProductDetails($id)
        {   //implementing product_details view
        try {
            $sql = '
                    SELECT * from product_details
                    WHERE product_id = :id  
             ';
        
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $product = $query->fetch(\PDO::FETCH_OBJ);
        return $product;
        
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        } 
    }

    //Get product variant details by product id
     public function getProductVariantsDetails($id)
        {   //implementing product_details view
        try {
            $sql = '
                    SELECT * from product_variants_details
                    WHERE product_id = :id  
             ';
        
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $product_variants_details = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $product_variants_details;
        
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
       //update a product on the admin page
       public function updateProduct() {
        try {
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
            $query = $this->db->prepare($sql);
            //echo $query->queryString;

            $query->execute();
        //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }  
            
    }

    //Get product variant details by product id

    //Get all the tags that are associated with a specific product
    public function getProductTags($productId) {
        $query = "
            SELECT t.title 
            FROM tags t 
            INNER JOIN products_tags pt ON t.tag_id = pt.tag_id 
            WHERE pt.product_id = :productId
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $tags;
    }

    //Get products that have the same tags as the current product
    public function getProductsByTags($productId, $tags) {
    // Gets the number of tags and creates a placeholder string for the query
        $placeholders = str_repeat('?,', count($tags) - 1) . '?';
        $limit = 3;
        $query = "
        SELECT p.product_id, p.title AS product_title, p.product_description, a.title as artist_title,
        COUNT(pt.tag_id) as common_tag_count, GROUP_CONCAT(t.title) as common_tags, ip.image_name, ip.image_path
        FROM products p
        INNER JOIN products_tags pt ON p.product_id = pt.product_id
        INNER JOIN tags t ON t.tag_id = pt.tag_id
        INNER JOIN images_for_products ip ON ip.product_id = p.product_id
        INNER JOIN cds c ON c.product_id = p.product_id
        INNER JOIN artists a ON a.artist_id = c.artist_id
        WHERE pt.tag_id IN (
            SELECT tag_id
            FROM products_tags
            WHERE product_id = :current_product_id
        )
        AND p.product_id != :current_product_id
        GROUP BY p.product_id
        ORDER BY common_tag_count DESC
        LIMIT :limit
    ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':current_product_id', $productId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $products;
    }




    }
