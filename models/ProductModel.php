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
                con.title AS condition_title,        
                pv.quantity_in_stock,
                pv.creation_date,
                pv.price
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
        public function getProductsByTag($tag, $offset, $limit) {
            try {
                $sql = '
                    SELECT
                        p.product_id,
                        pv.product_variant_id,
                        p.title as product_title,
                        a.title AS artist_title,
                        t.title AS tag_title,
                        ip.image_name,
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
                    LIMIT :offset, :limit
                ';
                
                $query = $this->db->prepare($sql);
                $query->bindParam(':tag', $tag, PDO::PARAM_STR);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                $query->bindParam(':limit', $limit, PDO::PARAM_INT);
                $query->execute();
        
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
        //using product_details view
        function getProductDetails($id)
        {   
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

    //Get product variants details by product id (using product_variants_details view)
    //Currently it will give 2 variants since there are 2 conditions (new and used) for each product
    //The parameter is a product id (NOT a product variant id)
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

     //Get a single variant details by variant id (using product_variants_details view)
    //The parameter is a the product variant id (NOT a product  id)
    public function getVariantDetails($id)
    {   //implementing product_details view
    try {
       $sql = '
                SELECT * from product_variants_details
                WHERE product_variant_id = :id  
         ';
  
    $query = $this->db->prepare($sql);
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
    $product_variant_details = $query->fetch(\PDO::FETCH_OBJ);
    return $product_variant_details;
    
    } catch (\PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

    //Get product variant details by product variant id
    //using the product_variants_details view
    //the parameter is a product variant id (NOT a product id)

    public function getProductVariantDetails($VariantId) {

        try {
            $sql = '
            SELECT
            *
        FROM
            product_variants_details pv
            WHERE pv.product_variant_id = :VariantId
            GROUP BY pv.product_variant_id
             ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':VariantId', $VariantId, \PDO::PARAM_INT);
            $query->execute();
            $product_variant_details = $query->fetch(\PDO::FETCH_ASSOC);
            return $product_variant_details;
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
    //delete a prodiuct-tag entery from the products_tags table
    public function deleteProductTag($productId, $tagId){
        try{
            $sql='     
            DELETE FROM products_tags
            WHERE product_id = :productId AND tag_id = :tagId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':productId', $productId);
            $query->bindParam(':tagId', $tagId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

  

    //Gets all the products variants
    public function getAllVariants($sortBy = null, $orderBy = null){
        try{
            $sql = 'SELECT * FROM product_variants_details';

            if ($sortBy&&$orderBy) {
                // Adjust the query for sorting
                $sql .= " ORDER BY $sortBy $orderBy";
            }
    
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());  
        }
    }


    public function addProduct($productTitle, $description, $creationDate){
        try{
            $sql='     
            INSERT INTO products (title, product_description, creation_date)
            VALUES (:productTitle, :description, :creationDate)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':productTitle', $productTitle);
            $query->bindParam(':description', $description);
            $query->bindParam(':creationDate', $creationDate);
            $query->execute();
            $productId = $this->db->lastInsertId();
            
            return $productId;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function updateProduct($productId, $productTitle, $description){
        try{
            $sql='     
            UPDATE products
            SET title = :productTitle, product_description = :description
            WHERE product_id = :productId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':productTitle', $productTitle);
            $query->bindParam(':description', $description);
            $query->bindParam(':productId', $productId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("PDOException in updateProduct: " . $e->getMessage());
        }
    }

    public function deleteProduct($varinatId){
        try{
            $sql='     
            DELETE FROM product_variants
            WHERE product_variant_id = :variantId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':variantId', $varinatId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    //checks if a tag already exists in the table
    public function getTagIdByTitle($tag){
        try{
            $sql='     
            SELECT * FROM tags WHERE title = :tag
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':tag', $tag);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            // Check if a tag was found
            if ($result) {
                return $result->tag_id;
            } else {
                // Return null or any value that makes sense in your context
                return null;
            }
            return $tag;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    //inserts a new tag into the tags table and return's its tag id
    public function insertTag($tag){
        try{
            $sql='     
            INSERT INTO tags (title)
            VALUES (:tag)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':tag', $tag);
            $query->execute();
            $tagId = $this->db->lastInsertId();
            //returns new tag id
            return $tagId;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function addProductTag($productId,$tagId){
        try{
            $sql='     
            INSERT INTO products_tags (product_id, tag_id)
            VALUES (:productId, :tagId)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':tagId', $tagId);
            $query->bindParam(':productId', $productId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    //update tags for an existing product
    public function updateProductTag($productId, $tagId){
        try{
            $sql='     
            UPDATE products_tags
            SET tag_id = :tagId
            WHERE product_id = :productId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':tagId', $tagId);
            $query->bindParam(':productId', $productId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    
    public function insertCd($releaseDate, $artistId, $newProductId){
        try{
            $sql='     
            INSERT INTO cds (release_date, artist_id, product_id)
            VALUES (:releaseDate, :artistId, :productId)
            ';
            $query = $this->db->prepare($sql);
                $query->bindParam(':releaseDate', $releaseDate);
                $query->bindParam(':artistId', $artistId); 
                $query->bindParam(':productId', $newProductId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed at insertCd: " . $e->getMessage() . $releaseDate . $artistId . $newProductId);
        }

    }

    public function updateCd($releaseDate, $artistId, $newProductId){
        try{
            $sql='     
            UPDATE cds
            SET release_date = :releaseDate, artist_id = :artistId
            WHERE product_id = :productId
            ';
            $query = $this->db->prepare($sql);
                $query->bindParam(':releaseDate', $releaseDate);
                $query->bindParam(':artistId', $artistId); 
                $query->bindParam(':productId', $newProductId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed at updateCd: " . $e->getMessage() . $releaseDate . $artistId . $newProductId);
        }

    }

    //inserts a new product variant into the product_variants table
    public function insertProductVariant($newProductId, $condition, $price, $quantityInStock){
        try{
            $sql='     
            INSERT INTO product_variants (product_id, price, quantity_in_stock, condition_id)
            VALUES (:newProductId, :price, :quantityInStock, :condition)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':newProductId', $newProductId);
            // $query->bindParam(':condition', $condition);
            $query->bindParam(':price', $price);
            $query->bindParam(':quantityInStock', $quantityInStock);
            $query->bindParam(':condition', $condition);
            $query->execute();
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    public function updateProductVariant($productVariantId, $price, $quantityInStock){
        try{
            $sql='     
            UPDATE product_variants
            SET price = :price, quantity_in_stock = :quantityInStock
            WHERE product_variant_id = :productVariantId
            ';
            $query = $this->db->prepare($sql);           
            $query->bindParam(':productVariantId', $productVariantId);
            $query->bindParam(':price', $price);
            $query->bindParam(':quantityInStock', $quantityInStock);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    public function deleteProductVariant($variantId) {
        try {
            $sql = 'DELETE FROM product_variants WHERE product_variant_id = :variantId';
            $query = $this->db->prepare($sql);
            $query->bindParam(':variantId', $variantId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

  

    //checks if an artist already exists in the table and returns its artist id 
    public function checkArtist($artistTitle){
        try{
            $sql='     
            SELECT artist_id FROM artists WHERE title = :artistTitle
            LIMIT 1
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':artistTitle', $artistTitle);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            if (!$result) 
                return null;
             return $result->artist_id;
            
                // n
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    //inserts a new artist into the artists table and return's its artist id
    public function insertArtist($artistTitle){
        try{
            $sql='     
            INSERT INTO artists (title)
            VALUES (:artistTitle)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':artistTitle', $artistTitle);
            $query->execute();
            $artistId = $this->db->lastInsertId();
            //returns new artist id
            return $artistId;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function updateArtist($artistTitle){
        try{
            $sql='     
            UPDATE artists
            SET title = :artistTitle
            WHERE artist_id = :artistId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':artistTitle', $artistTitle);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getArtistId($artistTitle){
        try{
            $sql='     
            SELECT artist_id FROM artists WHERE title = :artistTitle
            LIMIT 1
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':artistTitle', $artistTitle);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            if (!$result) 
                return null;
      
             return $result->artist_id;
            
                
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function addCd($cdId, $releaseDate, $artistId){
        try{
            $sql='     
            INSERT INTO cds (product_id, artist_id, release_date)
            VALUES (:productId, :artistId, :releaseDate)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':productId', $productId);
            $query->bindParam(':artistId', $artistId);
            $query->bindParam(':releaseDate', $releaseDate);
            $query->execute();
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    public function insertImage( $newProductId, $image_name, $main_image){
        try{
            $sql='     
            INSERT INTO images_for_products (product_id, image_name, main_image)
            VALUES (:newProductId, :image_name, :main_image)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':newProductId', $newProductId);
            $query->bindParam(':image_name', $image_name);
            $query->bindParam(':main_image', $main_image);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    public function updateImage($productId, $image_name, $main_image){
        try{
            $sql='     
            UPDATE images_for_products
            SET image_name = :image_name, main_image = :main_image
            WHERE product_id = :productId
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':productId', $productId);
            $query->bindParam(':image_name', $image_name);
            $query->bindParam(':main_image', $main_image);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

    }

    //Inserts a new cd into the cds table
    public function cdInsert($newProductId, $releaseDate, $artistId ){
        try{
            $sql='     
            INSERT INTO cds (product_id, release_date, artist_id)
            VALUES (:newProductId, :releaseDate, :artistId)
            ';
            $query = $this->db->prepare($sql);
            
            $query->bindParam(':newProductId', $newProductId);
            $query->bindParam(':releaseDate', $releaseDate);
            $query->bindParam(':artistId', $artistId);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    //search function
    public function search($searchTerm){
        try{
            $sql='     
            SELECT
            * FROM product_variants_details
            WHERE product_title LIKE :searchTerm OR artist_title LIKE :searchTerm
            OR tag_titles LIKE :searchTerm OR product_description LIKE :searchTerm
            OR condition_title LIKE :searchTerm
            ';
            $query = $this->db->prepare($sql);

        // $searchTerm is a variable containing the search term
        $searchTerm = '%' . $searchTerm . '%';

        $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
     
     
     
        }
    }
    public function addTagToProduct($productId, $tagId){
        try {
            // Check if the product_id exists in the products table
            if (!$this->productExists($productId)) {
                throw new \Exception("Product with ID $productId does not exist.");
            }
    
            $sql = 'INSERT INTO products_tags (product_id, tag_id) VALUES (:productId, :tagId)';
            $query = $this->db->prepare($sql);
    
            $query->bindParam(':productId', $productId);
            $query->bindParam(':tagId', $tagId);
            $query->execute();
    
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    
    // Helper method to check if a product exists
    private function productExists($productId) {
        $sql = 'SELECT COUNT(*) FROM products WHERE product_id = :productId';
        $query = $this->db->prepare($sql);
        $query->bindParam(':productId', $productId);
        $query->execute();
    
        return $query->fetchColumn() > 0;
    }
    


   }