<?php
function handleFileUpload() {
    $uploadDir = __DIR__ . '/../uploads/';
    $maxSize = 2 * 1024 * 1024; // 2MB
    $allowedTypes = ['jpg', 'png', 'pdf'];
    $result = ['success' => false, 'message' => '', 'filePath' => ''];
    
    // Tạo thư mục uploads nếu chưa tồn tại
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['evidence']['name'];
        $fileSize = $_FILES['evidence']['size'];
        $fileTmp = $_FILES['evidence']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Kiểm tra định dạng file
        if (!in_array($fileExt, $allowedTypes)) {
            $result['message'] = 'Chỉ chấp nhận file JPG, PNG, PDF.';
            return $result;
        }
        
        // Kiểm tra kích thước
        if ($fileSize > $maxSize) {
            $result['message'] = 'File quá lớn. Tối đa 2MB.';
            return $result;
        }
        
        // Đổi tên file để tránh trùng
        $newFileName = 'upload_' . time() . '_' . uniqid() . '.' . $fileExt;
        $targetFile = $uploadDir . $newFileName;
        
        // Di chuyển file
        if (move_uploaded_file($fileTmp, $targetFile)) {
            $result['success'] = true;
            $result['message'] = 'Tải file thành công.';
            $result['filePath'] = $newFileName;
        } else {
            $result['message'] = 'Lỗi khi tải file.';
        }
    } else {
        $result['message'] = 'Không có file hoặc lỗi upload.';
    }
    
    return $result;
}
?>