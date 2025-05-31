<?php
namespace Day9\Controllers;

use Day9\Models\Product;

require_once BASE_URL . 'models/Product.php';
require_once BASE_URL . 'models/Category.php';

class ProductController {
    private $product;

    public function __construct() {
        $this->product = new Product();
    }

    public function index() {
        $filters = [
            'name' => isset($_GET['name']) ? trim($_GET['name']) : '',
            'min_price' => isset($_GET['min_price']) ? floatval($_GET['min_price']) : '',
            'max_price' => isset($_GET['max_price']) ? floatval($_GET['max_price']) : ''
        ];
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 5;

        $products = $this->product->readAll($filters, $sort, $order, $page, $perPage);
        $totalProducts = $this->product->getTotal($filters);
        $totalPages = ceil($totalProducts / $perPage);

        $view_file = BASE_URL . 'views/products/index.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $category_id = $_POST['category_id'];
            $created_at = date('Y-m-d H:i:s');
            if ($this->product->create($name, $price, $quantity, $category_id, $created_at)) {
                header("Location: index.php?action=index");
                exit;
            }
        }
        $this->index();
    }

    public function detail($id) {
        $product = $this->product->readOne($id);
        if (!$product) {
            echo "Sản phẩm không tồn tại.";
            return;
        }
        $view_file = BASE_URL . 'views/products/detail.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function edit($id) {
        $product = $this->product->readOne($id);
        if (!$product) {
            echo "Sản phẩm không tồn tại.";
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $category_id = $_POST['category_id'];
            if ($this->product->update($id, $name, $price, $quantity, $category_id)) {
                header("Location: index.php?action=index");
                exit;
            }
        }
        $view_file = BASE_URL . 'views/products/edit.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function delete($id) {
        if ($this->product->delete($id)) {
            header("Location: index.php?action=index");
            exit;
        }
    }
}
?>