<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Sửa Đơn Hàng</h2>
        <form method="POST" action="index.php?action=edit_order&id=<?php echo $order['id']; ?>" class="row g-3">
            <div class="col-md-4">
                <label for="order_date" class="form-label">Ngày đặt</label>
                <input type="date" name="order_date" id="order_date" class="form-control" value="<?php echo $order['order_date']; ?>" required>
            </div>
            <div class="col-md-4">
                <label for="customer_name" class="form-label">Tên khách hàng</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="<?php echo $order['customer_name']; ?>" required>
            </div>
            <div class="col-md-4">
                <label for="note" class="form-label">Ghi chú</label>
                <input type="text" name="note" id="note" class="form-control" value="<?php echo $order['note']; ?>">
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php?action=manage_orders" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>