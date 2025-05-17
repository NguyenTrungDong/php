<?php 
// Định nghĩa các hằng số: 
const COMMISSION_RATE = 0.2; // Tỷ lệ hoa hồng
const VAT_RATE = 0.1; // Thuế VAT

// Dữ liệu đầu vào 
$campaign_name = "Spring Sale 2025"; // Tên chiến dịch
$order_count = 150; // Số lượng đơn hàng
$product_price = 99.99; // Giá sản phẩm
$product_type = "Thời trang"; // Loại sản phẩm
$is_campaign_ended = true; // Trạng thái chiến dịch (true: đã kết thúc, false: chưa kết thúc)

// Danh sách đơn hàng
$orders = [
    "ID001" => 99.99,
    "ID002" => 49.99,
    "ID003" => 99.99,
    "ID004" => 79.99,
    "ID005" => 99.99
];

// Chuyển đổi kiểu dữ liệu (nếu cần thiết)
$order_count = (int)$order_count; // Đảm bảo só lượng đơn hàng là số nguyên
$product_price = (float)$product_price; // Đảm bảo giá sản phẩm là số thực

// Tính toán doanh thu thực tế từ danh sách đơn hàng (sử dụng vòng lặp for)
$total_revenue = 0;
$order_keys = array_keys($orders);
for ($i = 0; $i < count($orders); $i++) {
    $total_revenue += (float)$orders[$order_keys[$i]]; // Cộng dồn giá trị đơn hàng
}
// Tính hoa hồng
$commission_cost = $total_revenue * COMMISSION_RATE;

// Tính thuế VAT
$vat_cost = $total_revenue * VAT_RATE;

// Tính lợi nhuận
$profit = $total_revenue - ($commission_cost + $vat_cost);

// Đánh giá hiệu quả chiến dịch
$campaign_result = "";
if ($profit > 0) {
    $campaign_result = "Chiến dịch thành công!";
} elseif ($profit == 0) {
    $campaign_result = "Chiến dịch hòa vốn!";
} else {
    $campaign_result = "Chiến dịch thất bại!";
}

// Đánh giá loại sản phẩm
$product_message = "";
switch ($product_type) {
    case "Thời trang":
        $product_message = "Sản phẩm thời trang đang được ưa chuộng!";
        break;
    case "Điện tử":
        $product_message = "Sản phẩm điện tử đang hot!";
        break;
    case "Đồ gia dụng":
        $product_message = "Sản phẩm đồ gia dụng có nhu cầu đều đặn!";
        break;
    default:
        $product_message = "Sản phẩm không xác định!";
}

// Hiển thị kết quả

echo "Phân tích chiến dịch Affiliate Marketing<br>";
echo "<hr>";
echo "Tên chiến dịch: $campaign_name<br>";
echo "Số lượng đơn hàng: $order_count<br>";
echo "Giá sản phẩm: $product_price<br>";
echo "Loại sản phẩm: $product_type<br>";
echo "Thông tin sản phẩm : $product_message<br>";
echo "<hr>";

// Sử dụng print để hiển thị số liệu tài chính
print "Tổng doanh thu (dựa trên danh sách đơn hàng): " . number_format($total_revenue, 2) . " USD<br>";
print "Chi phí hoa hồng (20%): " . number_format($commission_cost, 2) . " USD<br>";
print "Thuế VAT (10%): " . number_format($vat_cost, 2) . " USD<br>";
print "Lợi nhuận: " . number_format($profit, 2) . " USD<br>";
print "Đánh giá: $campaign_result<br>";
echo "<hr>";

// Hiển thị chi tiết đơn hàng
echo "Chi tiết đơn hàng:<br>";
// Sử dụng print_r để hiển thị mảng đơn hàng (debug)
echo "<br>Debug - Danh sách đơn hàng (print_r):<br>";
print_r($orders);

// Thông báo tóm tắt
$summary = "Chiến dịch $campaign_name " . ($is_campaign_ended ? "đã kết thúc" : "đang chạy") . 
           " với lợi nhuận: " . number_format($profit, 2) . " USD";
echo "<br>$summary<br>";

// Debug: Hiển thị thông tin file và dòng
echo "<br>Debug Info:<br>";
echo "File: " . __FILE__ . "<br>";
echo "Line: " . __LINE__ . "<br>";