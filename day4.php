<?php
// Bắt đầu session để lưu trữ giao dịch tạm thời
session_start();

// Khởi tạo biến toàn cục trong $GLOBALS
$GLOBALS['total_income'] = 0;
$GLOBALS['total_expense'] = 0;
$GLOBALS['exchange_rate'] = 24000; // Tỷ giá giả lập (VND/USD)

// Khởi tạo mảng giao dịch trong $_SESSION nếu chưa tồn tại
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Xử lý biểu mẫu khi gửi dữ liệu
$errors = [];
$warning = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Sử dụng $_SERVER để kiểm tra phương thức
    // Lấy dữ liệu từ $_POST
    $transaction_name = trim($_POST['transaction_name'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $type = $_POST['type'] ?? '';
    $note = trim($_POST['note'] ?? '');
    $date = trim($_POST['date'] ?? '');

    // Sử dụng $_REQUEST để kiểm tra một số trường (kết hợp $_POST và $_GET)
    if (empty($_REQUEST['transaction_name'])) {
        $errors[] = "Tên giao dịch không được để trống.";
    }

    // Regex 1: Kiểm tra tên giao dịch (chỉ chữ cái, số, khoảng trắng)
    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $transaction_name)) {
        $errors[] = "Tên giao dịch chỉ được chứa chữ cái, số và khoảng trắng.";
    }

    // Regex 2: Kiểm tra số tiền (số dương, không chứa chữ)
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $amount) || $amount <= 0) {
        $errors[] = "Số tiền phải là số dương, tối đa 2 chữ số thập phân.";
    }

    // Regex 3: Kiểm tra định dạng ngày (dd/mm/yyyy)
    if (!preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/', $date)) {
        $errors[] = "Ngày phải có định dạng dd/mm/yyyy.";
    }

    // Kiểm tra loại giao dịch
    if (!in_array($type, ['income', 'expense'])) {
        $errors[] = "Loại giao dịch không hợp lệ.";
    }

    // Kiểm tra từ khóa nhạy cảm trong ghi chú
    if (preg_match('/(nợ xấu|vay nóng)/i', $note)) {
        $warning = "Cảnh báo: Ghi chú chứa từ khóa nhạy cảm!";
    }

    // Nếu không có lỗi, lưu giao dịch vào $_SESSION
    if (empty($errors)) {
        $transaction = [
            'name' => $transaction_name,
            'amount' => (float)$amount,
            'type' => $type,
            'note' => $note,
            'date' => $date
        ];
        $_SESSION['transactions'][] = $transaction;

        // Cập nhật tổng thu/chi trong $GLOBALS
        if ($type === 'income') {
            $GLOBALS['total_income'] += (float)$amount;
        } else {
            $GLOBALS['total_expense'] += (float)$amount;
        }
    }

    // Sử dụng $_COOKIE để lưu số lượng giao dịch (giả lập)
    setcookie('transaction_count', count($_SESSION['transactions']), time() + 3600);
}

// Tính số dư
$balance = $GLOBALS['total_income'] - $GLOBALS['total_expense'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý giao dịch tài chính</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .warning { color: orange; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
    </style>
    <script>
        // JavaScript kiểm tra sơ bộ
        function validateForm() {
            const name = document.forms["transactionForm"]["transaction_name"].value;
            const amount = document.forms["transactionForm"]["amount"].value;
            const date = document.forms["transactionForm"]["date"].value;
            if (!name || !amount || !date) {
                alert("Vui lòng điền đầy đủ các trường bắt buộc!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Nhập giao dịch tài chính</h2>

    <!-- Biểu mẫu gửi về chính nó sử dụng $_SERVER['PHP_SELF'] -->
    <form name="transactionForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="transaction_name">Tên giao dịch:</label><br>
            <input type="text" name="transaction_name" id="transaction_name" required>
        </div>
        <div class="form-group">
            <label for="amount">Số tiền:</label><br>
            <input type="number" step="0.01" name="amount" id="amount" required>
        </div>
        <div class="form-group">
            <label>Loại giao dịch:</label><br>
            <input type="radio" name="type" value="income" required> Thu
            <input type="radio" name="type" value="expense"> Chi
        </div>
        <div class="form-group">
            <label for="note">Ghi chú (tuỳ chọn):</label><br>
            <textarea name="note" id="note"></textarea>
        </div>
        <div class="form-group">
            <label for="date">Ngày thực hiện (dd/mm/yyyy):</label><br>
            <input type="text" name="date" id="date" placeholder="dd/mm/yyyy" required>
        </div>
        <button type="submit">Gửi</button>
    </form>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Hiển thị cảnh báo nếu có -->
    <?php if ($warning): ?>
        <div class="warning"><?php echo htmlspecialchars($warning); ?></div>
    <?php endif; ?>

    <!-- Hiển thị danh sách giao dịch -->
    <?php if (!empty($_SESSION['transactions'])): ?>
        <h3>Danh sách giao dịch</h3>
        <table>
            <tr>
                <th>Tên giao dịch</th>
                <th>Số tiền</th>
                <th>Loại</th>
                <th>Ghi chú</th>
                <th>Ngày</th>
            </tr>
            <?php foreach ($_SESSION['transactions'] as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['name']); ?></td>
                    <td><?php echo number_format($transaction['amount'], 2); ?></td>
                    <td><?php echo $transaction['type'] === 'income' ? 'Thu' : 'Chi'; ?></td>
                    <td><?php echo htmlspecialchars($transaction['note']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Thống kê -->
        <h3>Thống kê</h3>
        <p>Tổng thu: <?php echo number_format($GLOBALS['total_income'], 2); ?> VND</p>
        <p>Tổng chi: <?php echo number_format($GLOBALS['total_expense'], 2); ?> VND</p>
        <p>Số dư: <?php echo number_format($balance, 2); ?> VND</p>
        <p>Tỷ giá (giả lập): 1 USD = <?php echo number_format($GLOBALS['exchange_rate']); ?> VND</p>
        <p>Số lượng giao dịch (từ cookie): <?php echo $_COOKIE['transaction_count'] ?? 0; ?></p>
    <?php endif; ?>
</body>
</html>