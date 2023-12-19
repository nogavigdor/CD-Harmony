<?php
namespace Models;

use \DataAccess\DBConnector;
use PDO;

class OrderModel {
    private $db; 

    public function __construct()
    {
        $this->db = DBConnector::getInstance()->connectToDB();
    }

    // Get cart items by user id
    public function createOrder($userId, $items) {
        
        try {
            // Start a transaction
            $this->db->beginTransaction();

            // Insert the order
            $stmt = $this->db->prepare("
                INSERT INTO orders (creation_date, order_status_id, order_payment_id, user_id)
                VALUES (NOW(), :order_status_id, :order_payment_id, :user_id)
            ");
            $stmt->execute([
                'order_status_id' => 1, // 1 for 'Pending'
                'order_payment_id' => 1, // 1 for 'Not Paid'
                'user_id' => $userId
            ]);

            // Get the ID of the order we just inserted
            $orderId = $this->db->lastInsertId();

            // Insert the order lines
            $stmt = $this->db->prepare("
                INSERT INTO orders_lines (quantity, price, order_id, product_variant_id)
                VALUES (:quantity, :price, :order_id, :product_variant_id)
            ");
            foreach ($items as $productVarId => $item) {
                $stmt->execute([
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'order_id' => $orderId,
                    'product_variant_id' => $productVarId
                ]);
            }

            // Commit the transaction
            $this->db->commit();

            // Empty the cart
            SessionManager::unsetSessionVariable('cart');
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $this->db->rollback();
            throw $e;
        }
    }
}
