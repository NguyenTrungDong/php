<?php
$conn = new mysqli('localhost', 'root', '', 'ecommerce');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$query = "SELECT username, rating, comment FROM reviews WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<strong>{$row['username']}</strong> (Điểm: {$row['rating']}): ";
    echo "<p>{$row['comment']}</p>";
    echo "</div>";
}

$stmt->close();
$conn->close();
?>