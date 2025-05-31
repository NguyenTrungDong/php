<?php
namespace Day9\Models;
use Day9\Config\Database;
use PDO;
class Order {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($order_date, $customer_name, $note) {
        $sql = "INSERT INTO orders (order_date, customer_name, note) VALUES (:order_date, :customer_name, :note)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'order_date' => $order_date,
            'customer_name' => $customer_name,
            'note' => $note
        ]);
    }

    public function readAll($page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM orders ORDER BY order_date DESC LIMIT :offset, :perPage";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal() {
        $sql = "SELECT COUNT(*) FROM orders";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function readOne($id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $order_date, $customer_name, $note) {
        $sql = "UPDATE orders SET order_date = :order_date, customer_name = :customer_name, note = :note WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'order_date' => $order_date,
            'customer_name' => $customer_name,
            'note' => $note
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>