<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Quản lý Danh Mục</h2>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form method="POST" action="index.php?action=create_category" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="name" class="form-control" placeholder="Tên danh mục" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Thêm Danh Mục</button>
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
                    <th>Tên Danh Mục</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3" class="text-center">Không có danh mục nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo $category['category_name']; ?></td>
                        <td>
                            <a href="index.php?action=edit_category&id=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $category['id']; ?>">
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

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const categoryId = this.getAttribute('data-id');
        Swal.fire({
            title: 'TechFactory Xác Nhận',
            text: 'Bạn có chắc chắn muốn xóa danh mục này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `index.php?action=delete_category&id=${categoryId}`;
            }
        });
    });
});
</script>