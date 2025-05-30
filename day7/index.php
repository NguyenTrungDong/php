<?php
require_once 'AffiliateManager.php';

$manager = new AffiliateManager(); 

// Tạo và thêm 3 cộng tác viên
$partner1 = new AffiliatePartner("Nguyễn Văn A", "a@gmail.com", 5.0);
$partner2 = new AffiliatePartner("Trần Thị B", "b@gmail.com", 7.5);
$partner3 = new PremiumAffiliatePartner("Lê Văn C", "c@gmail.com", 10.0, 50000); // Sửa dữ liệu

$addOutput = 
[
    $manager->addPartner($partner1),
    $manager->addPartner($partner2),
    $manager->addPartner($partner3)
];

$orderValue = 2000000; // Giá trị đơn hàng
list($totalCommission, $commissionOutput) = $manager->totalCommission($orderValue); // Tính tổng hoa hồng theo giá trị đơn hàng
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý CTV Tiếp Thị Liên Kết</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            border: 1px solid #ffff;
        }
        .partner-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Hệ Thống Quản Lý CTV Tiếp Thị Liên Kết</h1>

        <!-- Hiển thị thông báo thêm cộng tác viên -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Danh Sách Các Cộng Tác Viên Affiliate Mới</h2>
            </div>
            <div class="card-body">
                <?php foreach ($addOutput as $outPut)
                {
                    echo "<pre class='text-light bg-dark' >" . htmlspecialchars($outPut) . "</pre>" ;
                } ?>
            </div>
        </div>

        <!-- Hiển thị danh sách cộng tác viên -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Danh Sách Cộng Tác Viên Affiliate</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($manager->getPartners() as $index => $partner): ?>
                        <div class="col-md-4 partner-card">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Partner <?php echo $index + 1; ?></h5>
                                    <pre class="text-light bg-dark"><?php echo htmlspecialchars($partner->getSummary() ?? ""); ?></pre>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Hiển thị tính toán hoa hồng -->
        <div class="card">
            <div class="card-header">
                <h2>Tính Toán Hoa Hồng Cho Đơn Hàng có giá trị <?php echo number_format($orderValue); ?> VNĐ</h2>
            </div>
            <div class="card-body">
                <pre class="text-white bg-dark"><?php echo htmlspecialchars($commissionOutput ?? ""); ?></pre>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>