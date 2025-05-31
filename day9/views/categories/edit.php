<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Sửa Danh Mục</h2>
        <form method="POST" action="index.php?action=edit_category&id=<?php echo $category['id']; ?>" class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $category['category_name']; ?>" required>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php?action=manage_categories" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>