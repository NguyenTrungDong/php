<?php

use Day9\Models\Category;
$categoryModel = new Category();
$categories = $categoryModel->readAll();
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Sửa Sản Phẩm</h2>
        <form method="POST" action="index.php?action=edit&id=<?php echo $product['id']; ?>" class="row g-3">
            <div class="col-md-4">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="col-md-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control" value="<?php echo $product['unit_price']; ?>" required>
            </div>
            <div class="col-md-2">
                <label for="quantity" class="form-label">Số lượng</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" required>
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?php echo $category['category_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php?action=index" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>