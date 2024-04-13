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

        // Insert the order details into the database after 
        // an ordder has been placed
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
                    'order_status_id' => 1, // 1 for 'Pending' (delivery statys)
                    'order_payment_id' => 2, // 1 for 'Paid'
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


        public function getInvoiceDetails   ($orderId) {
            try {
                $sql = 'SELECT * FROM invoice_details
                        WHERE order_id = :order_id';
               
                $query = $this->db->prepare($sql);
                $query->bindparam(':order_id', $orderId);
                $query->execute();
                return $query->fetchAll(\PDO::FETCH_ASSOC);
               
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        



  
        public function getOrderLines($orderId) {
            // Function code here
        
            $sql = "
                SELECT * from invoice_details
                WHERE order_id = :order_id
            ";
            $query = $this->db->prepare($sql);
            $query->bindparam(':order_id', $orderId);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllOrders(){
            try {
                $sql = 'SELECT * FROM order_summary';
               
                $query = $this->db->prepare($sql);
                $query->execute();
                return $query->fetchAll(\PDO::FETCH_ASSOC);
               
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getOrderSummary($orderId){
            try {
                $sql = 'SELECT * FROM order_summary WHERE order_id = :order_id';
               
                $query = $this->db->prepare($sql);
                $query->bindparam(':order_id', $orderId);
                $query->execute();
                return $query->fetch(\PDO::FETCH_ASSOC);
               
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getOrderDetailsById($userId){
            try {
                $sql = 'SELECT * FROM order_details WHERE user_id = :user_id';
               
                $query = $this->db->prepare($sql);
                $query->bindparam(':user_id', $userId);
                $query->execute();
                return $query->fetchAll(\PDO::FETCH_ASSOC);
               
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getOrderSummaryById($userId){
            try {
                $sql = 'SELECT * FROM order_summary WHERE user_id = :user_id';
               
                $query = $this->db->prepare($sql);
                $query->bindparam(':user_id', $userId);
                $query->execute();
                return $query->fetchAll(\PDO::FETCH_ASSOC);
               
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
/*For future implementation when payment_intent_id is integrated
        public function updateOrderStatus($paymentIntentId){
            try {
                $sql = 'UPDATE orders SET order_status_id = 2 WHERE order_payment_id = :order_payment_id';
                $query = $this->db->prepare($sql);
                $query->bindparam(':order_payment_id', $paymentIntentId);
                $query->execute();
                return $query->rowCount();
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        */
    }
