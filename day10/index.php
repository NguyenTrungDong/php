<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce AJAX</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-4">
        <!-- Tìm kiếm sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Tìm kiếm sản phẩm</h2>
            </div>
            <div class="card-body">
                <input type="text" id="search" class="form-control" placeholder="Nhập tên sản phẩm...">
                <div id="search-results" class="mt-3"></div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Sản phẩm</h2>
            </div>
            <div class="card-body">
                <div id="product-list" class="list-group">
                    <div class="list-group-item list-group-item-action product" data-id="1">Laptop Dell XPS</div>
                    <div class="list-group-item list-group-item-action product" data-id="2">Áo thun thời trang</div>
                </div>
                <div id="product-details" class="mt-3"></div>
            </div>
        </div>

        <!-- Giỏ hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Giỏ hàng</h2>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <button onclick="addToCart(1)" class="btn btn-primary">Thêm Laptop vào giỏ</button>
                    <button onclick="addToCart(2)" class="btn btn-primary">Thêm Áo thun vào giỏ</button>
                </div>
                <div id="cart-count" class="badge bg-secondary">Số lượng: 0</div>
            </div>
        </div>

        <!-- Đánh giá sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Đánh giá sản phẩm</h2>
            </div>
            <div class="card-body">
                <button onclick="loadReviews(1)" class="btn btn-outline-secondary mb-3">Xem đánh giá Laptop</button>
                <div id="reviews"></div>
            </div>
        </div>

        <!-- Thương hiệu từ XML -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Thương hiệu</h2>
            </div>
            <div class="card-body">
                <select id="category" onchange="loadBrands()" class="form-select mb-3">
                    <option value="">Chọn ngành hàng</option>
                    <option value="Electronics">Điện tử</option>
                    <option value="Fashion">Thời trang</option>
                </select>
                <div id="brands"></div>
            </div>
        </div>

        <!-- Bình chọn -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="h5 mb-0">Bình chọn cải thiện website</h2>
            </div>
            <div class="card-body">
                <form id="poll-form">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="vote" value="interface" id="interface">
                        <label class="form-check-label" for="interface">Giao diện</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="vote" value="speed" id="speed">
                        <label class="form-check-label" for="speed">Tốc độ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="vote" value="service" id="service">
                        <label class="form-check-label" for="service">Dịch vụ khách hàng</label>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Gửi</button>
                </form>
                <div id="poll-results" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="scripts.js"></script>
</body>
</html>