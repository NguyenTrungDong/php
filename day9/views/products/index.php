<?php

use Day9\Models\Category;
$categoryModel = new Category();
$categories = $categoryModel->readAll();
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Quản lý Sản Phẩm</h2>
    </div>
</div>

<!-- Form Lọc -->
<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="action" value="index">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Tìm theo tên" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <input type="number" name="min_price" step="0.01" class="form-control" placeholder="Giá tối thiểu" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <input type="number" name="max_price" step="0.01" class="form-control" placeholder="Giá tối đa" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i></button>
                <a href="index.php?action=index" class="btn btn-secondary">Xóa bộ lọc</a>
            </div>
        </form>
    </div>
</div>

<!-- Form Thêm Sản Phẩm -->
<div class="row mb-3">
    <div class="col-md-12">
        <form method="POST" action="index.php?action=create" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Tên sản phẩm" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="price" step="0.01" class="form-control" placeholder="Giá" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" placeholder="Số lượng" required>
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-control" required>
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Bảng Sản Phẩm -->
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <a href="index.php?action=index&sort=id&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'id' && $_GET['order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">
                            ID <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'id') ? ($_GET['order'] == 'ASC' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th>
                        <a href="index.php?action=index&sort=product_name&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'product_name' && $_GET['order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">
                            Tên Sản Phẩm <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'product_name') ? ($_GET['order'] == 'ASC' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th>
                        <a href="index.php?action=index&sort=unit_price&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'unit_price' && $_GET['order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">
                            Giá <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'unit_price') ? ($_GET['order'] == 'ASC' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th>
                        <a href="index.php?action=index&sort=stock_quantity&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'stock_quantity' && $_GET['order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">
                            Số Lượng <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'stock_quantity') ? ($_GET['order'] == 'ASC' ? '↑' : '↓') : ''; ?>
                        </a>
                    </th>
                    <th>Danh Mục</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6" class="text-center">Không có sản phẩm nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo number_format($product['unit_price'], 2); ?> VNĐ</td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td><?php echo $product['category_name'] ?? 'Chưa có danh mục'; ?></td>
                        <td>
                            <a href="index.php?action=detail&id=<?php echo $product['id']; ?>" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a href="index.php?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $product['id']; ?>">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Phân Trang -->
<div class="row">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?action=index&page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">Trước</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?action=index&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?action=index&page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&name=<?php echo isset($_GET['name']) ? urlencode($_GET['name']) : ''; ?>&min_price=<?php echo isset($_GET['min_price']) ? urlencode($_GET['min_price']) : ''; ?>&max_price=<?php echo isset($_GET['max_price']) ? urlencode($_GET['max_price']) : ''; ?>">Tiếp</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = this.getAttribute('data-id');
        Swal.fire({
            title: 'TechFactory Xác Nhận',
            text: 'Bạn có chắc chắn muốn xóa sản phẩm này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `index.php?action=delete&id=${productId}`;
            }
        });
    });
});
</script>