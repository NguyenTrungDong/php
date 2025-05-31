<?php
$conn = new mysqli('localhost', 'root', '', 'ecommerce');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<h3>{$row['name']}</h3>";
    echo "<p>{$row['description']}</p>";
    echo "<p>Giá: {$row['price']} USD</p>";
    echo "<p>Tồn kho: {$row['stock']}</p>";
    echo "<img src='{$row['image']}' alt='{$row['name']}' width='100'>";
}

$stmt->close();
$conn->close();
?>