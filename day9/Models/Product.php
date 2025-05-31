<?php
namespace Day9\Models;
use PDO;
use Day9\Config\Database;

class Product {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($name, $price, $quantity, $category_id, $created_at) {
        $sql = "INSERT INTO products (product_name, unit_price, stock_quantity, category_id, created_at) 
                VALUES (:name, :price, :quantity, :category_id, :created_at)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $category_id,
            'created_at' => $created_at
        ]);
    }

    public function readAll($filters = [], $sort = 'created_at', $order = 'DESC', $page = 1, $perPage = 5) {
        $sql = "SELECT p.*, c.category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $sql .= " AND p.product_name LIKE :name";
            $params['name'] = '%' . $filters['name'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.unit_price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.unit_price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        $sql .= " ORDER BY $sort $order";
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT :offset, :perPage";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue('perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal($filters = []) {
        $sql = "SELECT COUNT(*) FROM products WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $sql .= " AND product_name LIKE :name";
            $params['name'] = '%' . $filters['name'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND unit_price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND unit_price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function readOne($id) {
        $sql = "SELECT p.*, c.category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $price, $quantity, $category_id) {
        $sql = "UPDATE products SET product_name = :name, unit_price = :price, stock_quantity = :quantity, 
                category_id = :category_id, updated_at = :updated_at WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $category_id,
            'updated_at' => date('Y-m-d H:i:s') // 2025-05-30 22:47:00
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>