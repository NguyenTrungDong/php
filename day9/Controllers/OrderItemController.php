<?php
namespace Day9\Controllers;

use Day9\Models\Order;
use Day9\Models\Product;
use Day9\Models\OrderItem;


class OrderItemController {
    private $orderItem;
    private $product;
    private $order;

    public function __construct() {
        $this->orderItem = new OrderItem();
        $this->product = new Product();
        $this->order = new Order();
    }

    public function index($order_id) {
        $order = $this->order->readOne($order_id);
        if (!$order) {
            echo "Đơn hàng không tồn tại.";
            return;
        }
        $order_items = $this->orderItem->readByOrder($order_id);
        $products = $this->product->readAll(); // Để dropdown chọn sản phẩm
        $view_file = BASE_URL . 'views/order_items/index.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function create($order_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $product = $this->product->readOne($product_id);
            $price_at_order_time = $product['unit_price'];
            if ($this->orderItem->create($order_id, $product_id, $quantity, $price_at_order_time)) {
                header("Location: index.php?action=manage_order_items&order_id=$order_id");
                exit;
            }
        }
        $this->index($order_id);
    }

    public function delete($id, $order_id) {
        if ($this->orderItem->delete($id)) {
            header("Location: index.php?action=manage_order_items&order_id=$order_id");
            exit;
        }
    }
}
?>