<?php
namespace Models;
use \DataAccess\DBConnector;
use PDO;

class RecommendationModel {

    private $db;

    public function __construct() {
        $this->db = DBConnector::getInstance()->connectToDB();
    }

    public function getRecommendationsBasedOnUserBehavior($userId) {

        
        // This method could recommend products that the user has purchased in the past.
        $sql = "SELECT products.*, FROM products 
                JOIN product_variants ON products.product_id = product_variants.product_id
                JOIN orders_lines ON product_variants.product_variant_id = orders_lines.product_variant_id
                JOIN orders ON orders_lines.order_id = orders.order_id
                WHERE orders.user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecommendationsBasedOnPopularProduct() {
        $limit = 3;
        // recommends products  that are popular (have been purchased by most by other users)
        $sql = "SELECT products.*, COUNT(*) as purchase_count FROM products 
                JOIN product_variants ON products.product_id = product_variants.product_id
                JOIN orders_lines ON product_variants.product_variant_id = orders_lines.product_variant_id
                GROUP BY product_variants.product_variant_id
                ORDER BY purchase_count DESC
                LIMIT :limit";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


      //Get products that have the same tags as the current product
    //ordered by the biggest number of common tags
    public function getRecommendationBasedOnSharedTags($productId) {
        // Gets the number of tags and creates a placeholder string for the query
            $limit = 3;
            $query = "
            SELECT p.product_id, p.title AS product_title, p.product_description, a.title as artist_title,
            COUNT(pt.tag_id) as common_tag_count, GROUP_CONCAT(t.title) as common_tags, ip.image_name
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