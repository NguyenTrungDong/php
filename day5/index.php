<?php
require_once 'includes/logger.php';
require_once 'includes/upload.php';

$actionMessage = '';
$fileMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action) {
        // Xử lý upload file
        $uploadResult = handleFileUpload();
        $fileMessage = $uploadResult['message'];
        
        // Ghi log
        writeLog($action, $uploadResult['success'] ? $uploadResult['filePath'] : null);
        $actionMessage = 'Hành động đã được ghi log.';
    } else {
        $actionMessage = 'Vui lòng nhập hành động.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php if ($actionMessage): ?>
    <p class="<?php echo strpos($actionMessage, 'thành công') !== false ? 'success' : 'error'; ?>">
        <?php echo $actionMessage; ?>
    </p>
<?php endif; ?>
<?php if ($fileMessage): ?>
    <p class="<?php echo strpos($fileMessage, 'thành công') !== false ? 'success' : 'error'; ?>">
        <?php echo $fileMessage; ?>
    </p>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <label for="action">Hành động:</label><br>
    <input type="text" name="action" id="action" required><br><br>
    
    <label for="evidence">File minh chứng (JPG, PNG, PDF, tối đa 2MB):</label><br>
    <input type="file" name="evidence" id="evidence" accept=".jpg,.png,.pdf"><br><br>
    
    <input type="submit" value="Ghi log">
</form>
</body>
</html>