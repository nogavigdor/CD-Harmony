<?php
namespace Models;
use Exception;

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
                    if (isset($item['quantity']) && isset($item['price'])) {
                        $item['quantity'] = (int)$item['quantity'];
                        $item['price'] = (float)$item['price'];
                    } else {
                        throw new Exception('Invalid cart item');
                    }
                    $stmt->execute([
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'order_id' => $orderId,
                        'product_variant_id' => $productVarId
                    ]);
                }

                // Commit the transaction
                $this->db->commit();

                return $orderId;
            
            } catch (Exception $e) {
                // An error occurred, rollback the transaction
                $this->db->rollback();
                throw $e;
            }
        }


        public function getAllOrders() {
            $stmt = $this->db->prepare("
                SELECT o.id, o.creation_date, o.order_status_id, o.order_payment_id, o.user_id, os.name AS order_status_name, op.name AS order_payment_name, u.email AS user_email
                FROM orders o
                INNER JOIN order_status os ON os.id = o.order_status_id
                INNER JOIN order_payment op ON op.id = o.order_payment_id
                INNER JOIN users u ON u.id = o.user_id
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getOrder($orderId) {
            $stmt = $this->db->prepare("
                SELECT o.id, o.creation_date, o.order_status_id, o.order_payment_id, o.user_id, os.name AS order_status_name, op.name AS order_payment_name, u.email AS user_email
                FROM orders o
                INNER JOIN order_status os ON os.id = o.order_status_id
                INNER JOIN order_payment op ON op.id = o.order_payment_id
                INNER JOIN users u ON u.id = o.user_id
                WHERE o.id = :order_id
            ");
            $stmt->execute([
                'order_id' => $orderId
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }



  
            public function getOrderLines($orderId) {
                // Function code here
            
        
            $stmt = $this->db->prepare("
                SELECT * from invoice_details
                WHERE order_id = :order_id
            ");
            $stmt->execute([
                'order_id' => $orderId
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
