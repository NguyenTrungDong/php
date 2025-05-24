<?php
session_start();

// Lớp ngoại lệ tùy chỉnh
class CartException extends Exception {
    public function errorMessage() {
        return "Lỗi Giỏ Hàng: " . $this->getMessage();
    }
}

// Khởi tạo giỏ hàng trong session nếu chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Lấy thông báo thành công và lỗi từ session, sau đó xóa
$success = '';
$error = '';
if (isset($_SESSION['success']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Hàm ghi log lỗi
function logError($message) {
    $logMessage = "[" . date('Y-m-d H:i:s') . "] $message\n";
    file_put_contents('log.txt', $logMessage, FILE_APPEND);
}

// Danh sách sách mẫu
$books = [
    ['title' => 'Clean Code', 'price' => 150000],
    ['title' => 'Design Patterns', 'price' => 200000],
    ['title' => 'Refactoring', 'price' => 180000]
];

// Tải dữ liệu từ file JSON nếu tồn tại
$cart_data = [];
if (file_exists('cart_data.json')) {
    $cart_data = json_decode(file_get_contents('cart_data.json'), true);
}

// Xử lý khi người dùng gửi form thêm sách
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        // Lấy dữ liệu từ form và kiểm tra rỗng (server-side validation)
        $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_DEFAULT);
        if (empty($customer_name)) {
            throw new CartException("Tên khách hàng là bắt buộc");
        }
        $customer_name = htmlspecialchars(strip_tags(trim($customer_name)), ENT_QUOTES, 'UTF-8');

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (empty($email)) {
            throw new CartException("Định dạng email không hợp lệ");
        }
        if ($email === false) {
            throw new CartException("Định dạng email không hợp lệ");
        }

        $phone = filter_input(INPUT_POST, 'phone', FILTER_DEFAULT);
        if (empty($phone)) {
            throw new CartException("Số điện thoại là bắt buộc");
        }
        $phone = filter_var($phone, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^[0-9]{10,11}$/']
        ]);
        if ($phone === false) {
            throw new CartException("Số điện thoại không hợp lệ (phải có 10-11 chữ số)");
        }

        $address = filter_input(INPUT_POST, 'address', FILTER_DEFAULT);
        if (empty($address)) {
            throw new CartException("Địa chỉ là bắt buộc");
        }
        $address = htmlspecialchars(strip_tags(trim($address)), ENT_QUOTES, 'UTF-8');

        $book_index = filter_input(INPUT_POST, 'book', FILTER_VALIDATE_INT);
        if ($book_index === false || $book_index === null || !isset($books[$book_index])) {
            throw new CartException("Lựa chọn sách không hợp lệ");
        }

        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);
        if ($quantity === false || $quantity === null) {
            throw new CartException("Số lượng không hợp lệ (phải là số lớn hơn 0)");
        }

        // Lưu thông tin khách hàng vào cookie (7 ngày)
        setcookie('customer_info', json_encode([
            'name' => $customer_name,
            'email' => $email
        ]), time() + (7 * 24 * 60 * 60), "/");

        // Thêm sách vào giỏ hàng
        $book = $books[$book_index];
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['title'] === $book['title']) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'title' => $book['title'],
                'price' => $book['price'],
                'quantity' => $quantity
            ];
        }

        // Lưu giỏ hàng vào file JSON
        $cart_data = [
            'customer_email' => $email,
            'phone' => $phone,
            'address' => $address,
            'products' => $_SESSION['cart'],
            'total_amount' => array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $_SESSION['cart'])),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            if (!file_put_contents('cart_data.json', json_encode($cart_data, JSON_PRETTY_PRINT))) {
                throw new CartException("Không thể lưu dữ liệu giỏ hàng vào JSON");
            }
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }

        $_SESSION['success'] = "Đặt hàng thành công!";
    } catch (CartException $e) {
        $error_message = $e->errorMessage();
        $error = $error_message;
        $_SESSION['error'] = $error_message;
        logError($error_message);
    } catch (Exception $e) {
        $error_message = "Đã xảy ra lỗi bất ngờ: " . $e->getMessage();
        $error = $error_message;
        $_SESSION['error'] = $error_message;
        logError($error_message);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xử lý xóa giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear') {
    try {
        $_SESSION['cart'] = [];
        $cart_data = []; // Reset $cart_data để đảm bảo không hiển thị thông tin cũ
        if (file_exists('cart_data.json') && !unlink('cart_data.json')) {
            throw new CartException("Không thể xóa dữ liệu giỏ hàng");
        }
        $_SESSION['success'] = "Đã xóa giỏ hàng thành công!";
    } catch (CartException $e) {
        $error = $e->errorMessage();
        $_SESSION['error'] = $error;
        logError($error);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Tải thông tin khách hàng từ cookie
$customer_info = isset($_COOKIE['customer_info']) ? json_decode($_COOKIE['customer_info'], true) : [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng Cửa Hàng Sách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Giỏ Hàng Cửa Hàng Sách</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form Thêm Vào Giỏ Hàng -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                       value="<?php echo isset($customer_info['name']) ? htmlspecialchars($customer_info['name']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" 
                       value="<?php echo isset($customer_info['email']) ? htmlspecialchars($customer_info['email']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Địa Chỉ</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="mb-3">
                <label for="book" class="form-label">Chọn Sách</label>
                <select class="form-select" id="book" name="book">
                    <?php foreach ($books as $index => $book): ?>
                        <option value="<?php echo $index; ?>">
                            <?php echo htmlspecialchars($book['title']) . " - " . number_format($book['price']) . " VND"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Số Lượng</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
            </div>
            <button type="submit" class="btn btn-primary">Thêm Vào Giỏ Hàng</button>
        </form>

        <!-- Form Xóa Giỏ Hàng -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn btn-danger">Xóa Giỏ Hàng</button>
        </form>

        <!-- Hiển Thị Giỏ Hàng -->
        <?php if (!empty($_SESSION['cart']) && !empty($cart_data)): ?>
            <h2>Giỏ Hàng Của Bạn</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên Sách</th>
                        <th>Đơn Giá</th>
                        <th>Số Lượng</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        $item_total = $item['price'] * $item['quantity'];
                        $total += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title'] ?? ''); ?></td>
                            <td><?php echo number_format($item['price'] ?? 0); ?> VND</td>
                            <td><?php echo $item['quantity'] ?? 0; ?></td>
                            <td><?php echo number_format($item_total); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tổng Cộng:</strong></td>
                        <td><strong><?php echo number_format($total); ?> VND</strong></td>
                    </tr>
                </tbody>
            </table>
            <h3>Chi Tiết Đặt Hàng</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($cart_data['customer_email'] ?? ''); ?></p>
            <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($cart_data['phone'] ?? ''); ?></p>
            <p><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($cart_data['address'] ?? ''); ?></p>
            <p><strong>Thời Gian Đặt Hàng:</strong> <?php echo $cart_data['created_at'] ?? ''; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>