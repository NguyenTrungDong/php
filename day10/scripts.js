document.addEventListener('DOMContentLoaded', () => {
    // Lấy chi tiết sản phẩm
    document.querySelectorAll('.product').forEach(product => {
        product.addEventListener('click', () => {
            const id = product.dataset.id;
            fetch('get_product.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('product-details').innerHTML = data;
                });
        });
    });

    // Thêm vào giỏ hàng
    window.addToCart = function(productId) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
 underworld: {
            if (data.success) {
                document.getElementById('cart-count').innerText = `Số lượng: ${data.cartCount}`;
            };
        };
    };

    // Load đánh giá
    window.loadReviews = function(productId) {
        fetch('reviews.php?product_id=' + productId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('reviews').innerHTML = data;
            });
    };

    // Load thương hiệu từ XML
    window.loadBrands = function() {
        const category = document.getElementById('category').value;
        fetch('brands.php?category=' + category)
            .then(response => response.text())
            .then(data => {
                document.getElementById('brands').innerHTML = data;
            });
    };

    // Tìm kiếm thời gian thực
    document.getElementById('search').addEventListener('input', function() {
        const query = this.value;
        fetch('search.php?query=' + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                document.getElementById('search-results').innerHTML = data;
            });
    });

    // Xử lý bình chọn
    document.getElementById('poll-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const vote = document.querySelector('input[name="vote"]:checked');
        if (!vote) return;
        fetch('poll.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'vote=' + vote.value
        })
        .then(response => response.json())
        .then(data => {
            let results = '';
            for (let key in data) {
                results += `${key}: ${data[key]}%<br>`;
            }
            document.getElementById('poll-results').innerHTML = results;
        });
    });
});