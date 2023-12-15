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

    public function getRecommendationsBasedOnProduct() {
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
}