<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Chi tiết Đơn Hàng #<?php echo $order['id']; ?></h2>
        <p><strong>Khách hàng:</strong> <?php echo $order['customer_name']; ?> | 
           <strong>Ngày đặt:</strong> <?php echo date('d/m/Y', strtotime($order['order_date'])); ?> | 
           <strong>Thời gian hiện tại:</strong> <?php echo date('d/m/Y H:i:s'); ?> (10:47 PM +07, 30/05/2025)</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="POST" action="index.php?action=create_order_item&order_id=<?php echo $order['id']; ?>" class="row g-3">
            <div class="col-md-4">
                <select name="product_id" class="form-control" required>
                    <option value="">Chọn sản phẩm</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo $product['product_name']; ?> (<?php echo number_format($product['unit_price'], 2); ?> VNĐ)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" placeholder="Số lượng" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Giá Tại Thời Điểm Đặt</th>
                    <th>Tổng</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($order_items)): ?>
                    <tr><td colspan="6" class="text-center">Không có sản phẩm nào trong đơn hàng.</td></tr>
                <?php else: ?>
                    <?php $total = 0; ?>
                    <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price_at_order_time'], 2); ?> VNĐ</td>
                        <td><?php echo number_format($item['quantity'] * $item['price_at_order_time'], 2); ?> VNĐ</td>
                        <td>
                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $item['id']; ?>" data-order-id="<?php echo $order['id']; ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $total += $item['quantity'] * $item['price_at_order_time']; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong><?php echo number_format($total, 2); ?> VNĐ</strong></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="index.php?action=manage_orders" class="btn btn-secondary">Quay lại</a>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const itemId = this.getAttribute('data-id');
        const orderId = this.getAttribute('data-order-id');
        Swal.fire({
            title: 'TechFactory Xác Nhận',
            text: 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi đơn hàng không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `index.php?action=delete_order_item&id=${itemId}&order_id=${orderId}`;
            }
        });
    });
});
</script>