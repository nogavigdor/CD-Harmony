<?php
class Purchase {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createOrder($userId, $totalAmount) {
        try {
            // Start a transaction
            $this->db->beginTransaction();

            // Insert a new order
            $orderInsert = $this->db->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
            $orderInsert->execute([$userId, $totalAmount]);

            // Get the last inserted order ID
            $orderId = $this->db->lastInsertId();

            // Commit the transaction
            $this->db->commit();

            return $orderId;
        } catch (PDOException $e) {
            // An error occurred, so we roll back the transaction
            $this->db->rollBack();
            return false;
        }
    }

    public function addProductToOrder($orderId, $productId, $quantity) {
        try {
            // Start a transaction
            $this->db->beginTransaction();

            // Insert the product into the order
            $productInsert = $this->db->prepare("INSERT INTO orderProducts (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $productInsert->execute([$orderId, $productId, $quantity]);

            // Commit the transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // An error occurred, so we roll back the transaction
            $this->db->rollBack();
            return false;
        }
    }
}

// Example usage:

try {
    // Create a new PDO instance for your database connection
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

    $purchase = new Purchase($db);

    // Create a new order
    $userId = 123; // Replace with the actual user ID
    $totalAmount = 100.00; // Replace with the actual total amount
    $orderId = $purchase->createOrder($userId, $totalAmount);

    if ($orderId) {
        // Add products to the order
        $productId = 456; // Replace with the actual product ID
        $quantity = 3; // Replace with the actual quantity
        $success = $purchase->addProductToOrder($orderId, $productId, $quantity);
        if ($success) {
            echo "Product added to the order successfully.";
        } else {
            echo "Failed to add the product to the order.";
        }
    } else {
        echo "Failed to create the order.";
    }
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}
