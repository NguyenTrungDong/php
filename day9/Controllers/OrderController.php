<?php
namespace Day9\Controllers;
use Day9\Models\Order;


class OrderController {
    private $order;

    public function __construct() {
        $this->order = new Order();
    }

    public function index() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 5;

        $orders = $this->order->readAll($page, $perPage);
        $totalOrders = $this->order->getTotal();
        $totalPages = ceil($totalOrders / $perPage);

        $view_file = BASE_URL . 'views/orders/index.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_date = $_POST['order_date'];
            $customer_name = $_POST['customer_name'];
            $note = $_POST['note'];
            if ($this->order->create($order_date, $customer_name, $note)) {
                header("Location: index.php?action=manage_orders");
                exit;
            }
        }
        $this->index();
    }

    public function edit($id) {
        $order = $this->order->readOne($id);
        if (!$order) {
            echo "Đơn hàng không tồn tại.";
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_date = $_POST['order_date'];
            $customer_name = $_POST['customer_name'];
            $note = $_POST['note'];
            if ($this->order->update($id, $order_date, $customer_name, $note)) {
                header("Location: index.php?action=manage_orders");
                exit;
            }
        }
        $view_file = BASE_URL . 'views/orders/edit.php';
        require_once BASE_URL . 'views/layouts/main.php';
    }

    public function delete($id) {
        if ($this->order->delete($id)) {
            header("Location: index.php?action=manage_orders");
            exit;
        }
    }
}
?>