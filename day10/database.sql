CREATE DATABASE ecommerce;
USE ecommerce;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    username VARCHAR(50),
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Dữ liệu mẫu
INSERT INTO products (name, description, price, stock, image) VALUES
('Laptop Dell XPS', 'Laptop cao cấp, i7, 16GB RAM', 1200.00, 10, 'laptop.jpg'),
('Áo thun thời trang', 'Áo thun cotton cao cấp', 20.00, 50, 'shirt.jpg');

INSERT INTO reviews (product_id, username, rating, comment) VALUES
(1, 'Alice', 5, 'Sản phẩm tuyệt vời!'),
(1, 'Bob', 4, 'Hiệu năng tốt, giá hơi cao.');