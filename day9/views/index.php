<div class="row">
    <div class="col-md-12">
        <h2 class="text-center mb-4">Chào mừng đến với TechFactory</h2>
        <p class="lead text-center">Hệ thống quản lý sản xuất nội bộ</p>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Chức năng chính</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="index.php?action=index" class="text-decoration-none">Quản lý sản phẩm</a></li>
                    <li class="list-group-item"><a href="index.php?action=manage_orders" class="text-decoration-none">Quản lý đơn hàng</a></li>
                    <li class="list-group-item"><a href="index.php?action=manage_order_items" class="text-decoration-none">Quản lý chi tiết đơn hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Thông tin hệ thống</h5>
                <p class="card-text">Ngày hiện tại: <?php echo date('d/m/Y H:i:s'); ?></p>
                <p class="card-text">TechFactory hỗ trợ quản lý danh mục sản phẩm, tạo và theo dõi đơn hàng, cũng như ghi nhận chi tiết sản phẩm trong mỗi đơn hàng. Hệ thống được cập nhật lần cuối vào <?php echo date('H:i:s', strtotime('08:59 PM +07')); ?> ngày 30/05/2025.</p>
            </div>
        </div>
    </div>
</div>