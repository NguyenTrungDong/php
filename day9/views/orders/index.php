<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Quản lý Đơn Hàng</h2>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="POST" action="index.php?action=create_order" class="row g-3">
            <div class="col-md-3">
                <input type="date" name="order_date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="customer_name" class="form-control" placeholder="Tên khách hàng" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="note" class="form-control" placeholder="Ghi chú (tùy chọn)">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Thêm Đơn Hàng</button>
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
                    <th>Ngày Đặt</th>
                    <th>Tên Khách Hàng</th>
                    <th>Ghi Chú</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="5" class="text-center">Không có đơn hàng nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['order_date'])); ?></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $order['note'] ?? 'Không có'; ?></td>
                        <td>
                            <a href="index.php?action=edit_order&id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $order['id']; ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                            <a href="index.php?action=manage_order_items&order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">Chi tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?action=manage_orders&page=<?php echo $page - 1; ?>">Trước</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?action=manage_orders&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?action=manage_orders&page=<?php echo $page + 1; ?>">Tiếp</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const orderId = this.getAttribute('data-id');
        Swal.fire({
            title: 'TechFactory Xác Nhận',
            text: 'Bạn có chắc chắn muốn xóa đơn hàng này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `index.php?action=delete_order&id=${orderId}`;
            }
        });
    });
});
</script>