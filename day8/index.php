<?php
require 'vendor/autoload.php';

use XyzBank\Accounts\SavingAccountClass;
use XyzBank\Accounts\CheckingAccountClass;
use XyzBank\Accounts\BankClass;
use XyzBank\Accounts\AccountCollectionClass;

// Mảng lưu log giao dịch
$transactionLogs = [];

// Kiểm thử các chức năng
$collection = new AccountCollectionClass();
$savings = new SavingAccountClass("10201122", "Nguyễn Thị A", 20000000); // tài khoản tiết kiệm
$collection->addAccount($savings);
$checking1 = new CheckingAccountClass("20301123", "Lê Văn B", 8000000); // tài khoản thanh toán
$collection->addAccount($checking1);
$checking2 = new CheckingAccountClass("20401124", "Trần Minh C", 12000000); // tài khoản thanh toán
$collection->addAccount($checking2);

$checking1->deposit(5000000);
$checking2->withdraw(2000000);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý tài khoản và giao dịch ngân hàng số XYZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            margin-bottom: 40px;
        }
        h1, h3 {
            color: #333;
            font-weight: 600;
        }
        .table {
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
        }
        .table thead {
            background-color: #343a40;
            color: #fff;
        }
        .list-group-item {
            background-color: #f1f3f5;
            border: none;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .info-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Hệ thống quản lý tài khoản và giao dịch ngân hàng số XYZ</h1>

        <!-- Hiển thị log giao dịch -->
        <h3>Lịch sử giao dịch</h3>
        <ul class="list-group mb-4">
            <?php foreach ($transactionLogs as $log): ?>
                <li class="list-group-item"><?php echo $log; ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- Hiển thị danh sách tài khoản -->
        <h3>Danh sách tài khoản</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Mã tài khoản</th>
                    <th scope="col">Chủ tài khoản</th>
                    <th scope="col">Loại tài khoản</th>
                    <th scope="col">Số dư</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($collection as $account): ?>
                    <tr>
                        <td><?php echo $account->getAccountNumber(); ?></td>
                        <td><?php echo $account->getOwnerName(); ?></td>
                        <td><?php echo $account->getAccountType(); ?></td>
                        <td><?php echo number_format($account->getBalance(), 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Hiển thị thông tin lãi suất và ngân hàng -->
        <div class="info-section">
            <h3>Thông tin bổ sung</h3>
            <p>Tên ngân hàng: <strong><?php echo BankClass::getBankName(); ?></strong></p>
            <p>Tổng số tài khoản trong hệ thống: <strong><?php echo BankClass::getTotalAccounts(); ?></strong></p>
            <p>Lãi suất hàng năm cho <?php echo $savings->getOwnerName(); ?>: 
                <strong><?php echo number_format($savings->calculateAnnualInterest(), 0, ',', '.') . ' VNĐ'; ?></strong>
            </p>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>