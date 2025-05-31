<?php
$conn = new mysqli('localhost', 'root', '', 'ecommerce');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = '%' . $conn->real_escape_string($query) . '%';
$sql = "SELECT name, price, image FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $query);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<img src='{$row['image']}' alt='{$row['name']}' width='50'>";
    echo "<span>{$row['name']} - {$row['price']} USD</span>";
    echo "</div>";
}

$stmt->close();
$conn->close();
?>