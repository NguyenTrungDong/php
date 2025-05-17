<?php
function writeLog($action, $filePath = null) {
    $logDir = __DIR__ . '/../logs/';
    $logFile = $logDir . 'log_' . date('Y-m-d') . '.txt';
    
    // Tạo thư mục logs nếu chưa tồn tại
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    // Lấy thông tin
    $dateTime = (new DateTime())->format('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $logEntry = "[$dateTime] IP: $ip | Action: $action";
    
    // Thêm thông tin file nếu có
    if ($filePath) {
        $logEntry .= " | File: $filePath";
    }
    
    $logEntry .= "\n";
    
    // Ghi vào file log
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
?>