<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if ($product_id) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'cartCount' => array_sum($_SESSION['cart'])
]);
?>