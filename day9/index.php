<?php
require_once 'vendor/autoload.php';
require_once 'helpers.php';
use Day9\Controllers\OrderController;
use Day9\Controllers\ProductController;
use Day9\Controllers\CategoryController;
use Day9\Controllers\OrderItemController;

$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$view_file = BASE_URL . 'views/index.php';

$productController = new ProductController();
$categoryController = new CategoryController();
$orderController = new OrderController();
$orderItemController = new OrderItemController();

switch ($action) {
    case 'home':
        $view_file = BASE_URL . 'views/index.php';
        require_once BASE_URL . 'views/layouts/main.php';
        break;
    // Product actions
    case 'index':
        $productController->index();
        break;
    case 'create':
        $productController->create();
        break;
    case 'detail':
        $productController->detail($_GET['id']);
        break;
    case 'edit':
        $productController->edit($_GET['id']);
        break;
    case 'delete':
        $productController->delete($_GET['id']);
        break;
    // Category actions
    case 'manage_categories':
        $categoryController->index();
        break;
    case 'create_category':
        $categoryController->create();
        break;
    case 'edit_category':
        $categoryController->edit($_GET['id']);
        break;
    case 'delete_category':
        $categoryController->delete($_GET['id']);
        break;
    // Order actions
    case 'manage_orders':
        $orderController->index();
        break;
    case 'create_order':
        $orderController->create();
        break;
    case 'edit_order':
        $orderController->edit($_GET['id']);
        break;
    case 'delete_order':
        $orderController->delete($_GET['id']);
        break;
    // Order Item actions
    case 'manage_order_items':
        $orderItemController->index($_GET['order_id']);
        break;
        
    case 'create_order_item':
        $orderItemController->create($_GET['order_id']);
        break;
    case 'delete_order_item':
        $orderItemController->delete($_GET['id'], $_GET['order_id']);
        break;
    default:
        $view_file = BASE_URL . 'views/index.php';
        require_once BASE_URL . 'views/layouts/main.php';
        break;
}
?>