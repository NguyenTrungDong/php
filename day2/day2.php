<?php
// Khai báo strict types để kiểm tra kiểu dữ liệu nghiêm ngặt
declare(strict_types=1);

// Dữ liệu đầu vào
$users = [
    1 => ['name' => 'Alice', 'referrer_id' => null],
    2 => ['name' => 'Bob', 'referrer_id' => 1],
    3 => ['name' => 'Charlie', 'referrer_id' => 2],
    4 => ['name' => 'David', 'referrer_id' => 3],
    5 => ['name' => 'Eva', 'referrer_id' => 1],
];

$orders = [
    ['order_id' => 101, 'user_id' => 4, 'amount' => 200.0],
    ['order_id' => 102, 'user_id' => 3, 'amount' => 150.0],
    ['order_id' => 103, 'user_id' => 5, 'amount' => 300.0],
];

$commissionRates = [
    1 => 0.10, // Cấp 1: 10%
    2 => 0.05, // Cấp 2: 5%
    3 => 0.02, // Cấp 3: 2%
];



/**
 * Hàm đệ quy để tìm ID người giới thiệu
 * @param array $users Mảng người dùng
 * @param int $userId ID người dùng
 * @return array Mảng chứa ID người giới thiệu
 */
function getReferrerChain(array $users, int $userId): array
{
    $chain = [];
    $currentId = $userId;

    while (isset($users[$currentId]['referrer_id']) && $users[$currentId]['referrer_id'] !== null) {
        $chain[] = $users[$currentId]['referrer_id'];
        $currentId = $users[$currentId]['referrer_id'];
    }

    return $chain;
}

/**
 * Hàm tính hoa hồng cho một đơn hàng
 * @param array $order Thông tin đơn hàng
 * @param array $users Mảng người dùng
 * @param array $commissionRates Tỷ lệ hoa hồng
 * @return array Mảng chứa thông tin hoa hồng
 */
function calculateOrderCommission(array $order, array $users, array $commissionRates): array
{
    if (!isset($order['user_id'], $order['order_id'], $order['amount'])) {
        logMessage("Đơn hàng thiếu thông tin: " . json_encode($order), 'ERROR');
        return [];
    }

    $commissions = [];
    $referrerChain = getReferrerChain($users, $order['user_id']);
    $amount = $order['amount'];

    foreach ($referrerChain as $level => $referrerId) {
        $level = $level + 1; // Cấp bắt đầu từ 1
        if (isset($commissionRates[$level])) {
            $rate = $commissionRates[$level];
            $commission = $amount * $rate;
            $commissions[] = [
                'user_id' => $referrerId,
                'user_name' => $users[$referrerId]['name'] ?? 'Unknown',
                'order_id' => $order['order_id'],
                'level' => $level,
                'commission' => $commission,
            ];
        } else {
            break; // Không có tỷ lệ hoa hồng cho cấp cao hơn
        }
    }

    return $commissions;
}

/**
 * Hàm tổng hợp hoa hồng cho tất cả đơn hàng
 * @param array $orders Danh sách đơn hàng
 * @param array $users Mảng người dùng
 * @param array $commissionRates Tỷ lệ hoa hồng
 * @return array Mảng chứa chi tiết và tổng hoa hồng
 */
function calculateCommission(array $orders, array $users, array $commissionRates): array
{
    static $calculationCount = 0;
    $calculationCount++;
    
    $allCommissions = [];
    $totalByUser = [];

    // Sử dụng hàm ẩn danh để xử lý từng đơn hàng
    $processOrder = fn(array $order): array => calculateOrderCommission($order, $users, $commissionRates);
    $commissions = array_merge(...array_map($processOrder, $orders));

    // Tổng hợp hoa hồng theo người dùng
    foreach ($commissions as $commission) {
        $userId = $commission['user_id'];
        if (!isset($totalByUser[$userId])) {
            $totalByUser[$userId] = [
                'name' => $commission['user_name'],
                'total' => 0.0,
                'details' => [],
            ];
        }
        $totalByUser[$userId]['total'] += $commission['commission'];
        $totalByUser[$userId]['details'][] = $commission;
    }

    return [
        'calculation_count' => $calculationCount,
        'details' => $commissions,
        'summary' => $totalByUser,
    ];
}

/**
 * Hàm xuất báo cáo hoa hồng
 * @return string Chuỗi báo cáo
 */
// Biến toàn cục để lưu kết quả
$commissionResult = [];
function generateReport(): string
{
    global $commissionResult;
    if (empty($commissionResult)) {
        return "Chưa có dữ liệu hoa hồng.<br>";
    }

    $report = "=== BÁO CÁO HOA HỒNG ===<br>";
    $report .= "Số lần tính toán: {$commissionResult['calculation_count']}<br>";

    foreach ($commissionResult['summary'] as $userId => $data) {
        $report .= sprintf(
            "Người dùng: %s (ID: %d)<br>Tổng hoa hồng: $%.2f<br>",
            $data['name'],
            $userId,
            $data['total']
        );
        $report .= "Chi tiết:<br>";
        foreach ($data['details'] as $detail) {
            $report .= sprintf(
                "- Đơn hàng %d, Cấp %d, Hoa hồng: $%.2f<br>",
                $detail['order_id'],
                $detail['level'],
                $detail['commission']
            );
        }
        $report .= "<br>";
    }

    return $report;
}

/**
 * Hàm ghi log với tham số mặc định
 * @param string $message Nội dung log
 * @param string $level Mức độ log
 * @return void
 */
function logMessage(string $message, string $level = 'INFO'): void
{
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message<br>";
    if (!file_put_contents('affiliate.log', $logEntry, FILE_APPEND)) {
        echo "Lỗi: Không thể ghi vào tệp affiliate.log<br>";
    }
}

// Chạy hệ thống
try {
    logMessage("Bắt đầu tính toán hoa hồng");
    $commissionResult = calculateCommission($orders, $users, $commissionRates);
    echo generateReport();
} catch (Exception $e) {
    logMessage("Lỗi khi chạy hệ thống: " . $e->getMessage(), 'ERROR');
    echo "Lỗi: " . $e->getMessage() . "<br>";
}

