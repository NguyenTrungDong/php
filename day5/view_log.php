<?php
$logContent = '';
$selectedDate = $_POST['log_date'] ?? date('Y-m-d');
$logFile = __DIR__ . '/logs/log_' . $selectedDate . '.txt';

if (file_exists($logFile)) {
    $file = fopen($logFile, 'r');
    $logContent = '<ul>';
    while (!feof($file)) {
        $line = fgets($file);
        if (trim($line)) {
            $logContent .= '<li>' . htmlspecialchars($line) . '</li>';
        }
    }
    $logContent .= '</ul>';
    fclose($file);
} else {
    $logContent = '<p>Không có nhật ký cho ngày này.</p>';
}
?>
<?php include 'includes/header.php'; ?>
<form method="post">
    <label for="log_date">Chọn ngày:</label>
    <input type="date" name="log_date" id="log_date" value="<?php echo $selectedDate; ?>">
    <input type="submit" value="Xem nhật ký">
</form>
<h2>Nhật ký ngày <?php echo $selectedDate; ?></h2>
<?php echo $logContent; ?>
</body>
</html>