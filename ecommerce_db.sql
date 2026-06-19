-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Seed categories
INSERT INTO categories (name) VALUES ('Electronics'), ('Clothing'), ('Home & Kitchen');

-- Seed products
INSERT INTO products (name, description, price, image, category_id) VALUES
('Wireless Headphones', 'High quality noise cancelling headphones', 3500.00, 'headphones.jpg', 1),
('Smart Watch', 'Fitness tracking smart watch with heart rate monitor', 7800.00, 'smartwatch.jpg', 1),
('Men T-Shirt', 'Premium cotton round neck t-shirt', 850.00, 'tshirt.jpg', 2),
('Running Shoes', 'Lightweight breathable running shoes', 4200.00, 'shoes.jpg', 2),
('Blender', 'High speed 1500W kitchen blender', 2900.00, 'blender.jpg', 3),
('Rice Cooker', 'Automatic 1.8L electric rice cooker', 3100.00, 'ricecooker.jpg', 3);

-- Admin user (password: admin123)
INSERT INTO users (fullname, email, password, role) VALUES
('Admin User', 'admin@ecommerce.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');