<?php
namespace Day9\Models;
use PDO;
use Day9\Config\Database;

class OrderItem {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($order_id, $product_id, $quantity, $price_at_order_time) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_order_time) 
                VALUES (:order_id, :product_id, :quantity, :price_at_order_time)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price_at_order_time' => $price_at_order_time
        ]);
    }

    public function readByOrder($order_id) {
        $sql = "SELECT oi.*, p.product_name FROM order_items oi 
                JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM order_items WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>