<?php
namespace Models;
use \DataAccess\DBConnector;

use PDO;

class CartModel {
    private $db; 

        public function __construct()
        {
            $this->db = DBConnector::getInstance()->connectToDB();
        }

  
    //Get cart items by user id
    public function getCartMasterId($userId) {
        try {
            $sql = 'SELECT cm.cart_master_id FROM cart_master cm WHERE cm.user_id = :user_id';
            $query = $this->db->prepare($sql);
            $query->bindParam(':user_id', $userId);
            $query->execute();
            return $query->fetch(PDO::FETCH_OBJ)->cart_master_id;
        } catch (\PDOException $e) {
            // Log or handle the exception appropriately
            throw new \PDOException("Error fetching cart master ID: " . $e->getMessage());
        }
    }
    
    //Get cart items by user id
    public function getCartItems($cartMasterId, $product_variant_id) {
        try{
            $sql='     
            SELECT
            cm.cart_master_id,
            cm.grand_total,
            cm.sub_total,
            cm.discount_total,
            cm.user_id,
            cm.session_id,
            cm.quantity,
            ci.product_variant_id,
            ci.quantity,
            ci.discunt,
            ci.total_price,
            con.title AS condition_title,
            p.title AS product_title,
            p.product_description AS description,
            a.title AS artist_title,
            ip.image_name,
            ip.image_path,
            pv.price,       
            pv.quantity_in_stock 
            FROM
            cart_master cm
            INNER JOIN cart_items ci ON cm.cart_master_id = ct.cart_master_id
            INNER JOIN product_variants pv ON cd.product_variant_id = pv.product_variant_id
            INNER JOIN products p ON pv.product_id = p.product_id
            INNER JOIN products_tags pt ON p.product_id = pt.product_id
            INNER JOIN tags t ON pt.tag_id = t.tag_id
            LEFT JOIN cds c ON p.product_id = c.product_id
            LEFT JOIN artists a ON c.artist_id = a.artist_id
            LEFT JOIN images_for_products ip ON p.product_id = ip.product_id
            INNER JOIN conditions con ON pv.condition_id = con.condition_id
            WHERE ct.cart_mast_id = :cart_master_id AND ct.product_variant_id = :product_variant_id
            GROUP BY p.product_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':cart_master_id', $cartMasterId);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    //update cart items
    public function updateCartItems($cartMasterId, $productVarId, $newQuantity) {
        try{
            $sql='     
            UPDATE cart_items
            SET quantity = :quantity
            WHERE cart_master_id = :cart_master_id AND product_variant_id = :product_variant_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':cart_master_id', $cartMasterId);
            $query->bindParam(':product_variant_id', $productVarId);
            $query->bindParam(':quantity', $newQuantity);
            $success = $query->execute();
            return $success;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
/* cart master total and cart items price total are updated through triggers in the database */

 //  creates cart master
public function createCartMaster($userId) {
        try{
            $sql='     
            INSERT INTO cart_master (user_id)
            VALUES (:user_id)
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':user_id', $userId);
            $success = $query->execute();
            return $success;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // creates cart items
    //the total_price will be updated through a trigger in the database
    public function createCartItem($cartMasterId, $productVarId, $quantity, $price, $discount) {
    
            try{
                $sql='     
                INSERT INTO cart_items (cart_master_id, product_variant_id, quantity, price, discount, total_price)
                VALUES (:cart_master_id, :product_variant_id, :quantity, :price, :discount, :total_price)
                ';
                $query = $this->db->prepare($sql);
                $query->bindParam(':cart_master_id', $cartMasterId);
                $query->bindParam(':product_variant_id', $productVarId);
                $query->bindParam(':quantity', $quantity);
                $query->bindParam(':price', $price);
                $query->bindParam(':discount', $discount);
                $success = $query->execute();
                return $success;
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        
    }

    //clear cart items
    public function clearCartItems($cartMasterId) {
        try{
            $sql='     
            DELETE FROM cart_items
            WHERE cart_master_id = :cart_master_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':cart_master_id', $cartMasterId);
            $success = $query->execute();
            return $success;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

 
 

 
}