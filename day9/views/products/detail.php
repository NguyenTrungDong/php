<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Chi tiết Sản Phẩm</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                <p class="card-text"><strong>ID:</strong> <?php echo $product['id']; ?></p>
                <p class="card-text"><strong>Giá:</strong> <?php echo number_format($product['unit_price'], 2); ?> VNĐ</p>
                <p class="card-text"><strong>Số lượng tồn kho:</strong> <?php echo $product['stock_quantity']; ?></p>
                <p class="card-text"><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i:s', strtotime($product['created_at'])); ?></p>
                <p class="card-text"><strong>Ngày cập nhật:</strong> 
                    <?php echo $product['updated_at'] ? date('d/m/Y H:i:s', strtotime($product['updated_at'])) : 'Chưa cập nhật'; ?>
                </p>
                <a href="index.php?action=index" class="btn btn-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</div>