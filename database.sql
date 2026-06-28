
-- First, drop existing tables if they exist
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS shopping_cart;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- TABLE 1: USERS
-- Stores all user accounts (buyers, sellers, admins)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(100) NOT NULL,
    user_type ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    is_verified TINYINT DEFAULT 0,
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 2: CATEGORIES
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL UNIQUE,
    category_icon VARCHAR(50) DEFAULT 'fa-tag',
    display_order INT DEFAULT 0,
    is_active TINYINT DEFAULT 1
);

-- TABLE 3: PRODUCTS
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'active', 'sold', 'cancelled') DEFAULT 'pending',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- TABLE 4: SHOPPING CART
-- Temporary storage for items before checkout
CREATE TABLE shopping_cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- TABLE 5: CART ITEMS
-- Individual items inside a shopping cart
CREATE TABLE cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price_at_add DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cart_id) REFERENCES shopping_cart(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);


-- TABLE 6: ORDERS
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    escrow_fee DECIMAL(10,2) DEFAULT 0,
    buyer_address TEXT NOT NULL,
    buyer_phone VARCHAR(20) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);


CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NOT NULL,
    is_approved TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_user (user_id)
);

-- INSERT DEFAULT DATA

-- Insert admin user (password: admin123)
INSERT INTO users (full_name, email, password, phone, location, user_type, is_verified) 
VALUES ('System Administrator', 'admin@shopeasysa.co.za', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0112345678', 'Johannesburg', 'admin', 1);

-- Insert sample seller
INSERT INTO users (full_name, email, password, phone, location, user_type, is_verified) 
VALUES ('Thabo Seller', 'seller@shopeasysa.co.za', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0712345678', 'Soweto', 'seller', 1);

-- Insert sample buyer
INSERT INTO users (full_name, email, password, phone, location, user_type, is_verified) 
VALUES ('Nomzamo Buyer', 'buyer@shopeasysa.co.za', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0823456789', 'Alexandra', 'buyer', 1);

-- Insert categories
INSERT INTO categories (category_name, category_icon, display_order) VALUES
('Clothing & Fashion', 'fa-tshirt', 1),
('Electronics', 'fa-mobile-alt', 2),
('Home & Living', 'fa-home', 3),
('Handmade Crafts', 'fa-paint-brush', 4),
('Books & Media', 'fa-book', 5),
('Sports & Outdoors', 'fa-futbol', 6);

-- Insert sample products
INSERT INTO products (seller_id, category_id, title, description, price, image_path, status) 
VALUES (2, 1, 'Denim Jacket', 'Beautiful denim jacket in excellent condition. Size Large, perfect for casual wear.', 450.00, 'uploads/product_images/denim_jacket.jpg', 'active');

INSERT INTO products (seller_id, category_id, title, description, price, image_path, status) 
VALUES (2, 2, 'Samsung Galaxy Phone', 'Second hand Samsung Galaxy phone, comes with charger and protective case.', 2500.00, 'uploads/product_images/samsung_galaxy.jpg', 'active');

INSERT INTO products (seller_id, category_id, title, description, price, image_path, status) 
VALUES (2, 4, 'Handmade Beaded Bangles', 'Traditional Zulu beadwork bangles, handmade with love and care.', 120.00, 'uploads/product_images/bangles.jpg', 'active');

-- Confirmation message
SELECT 'Database setup complete! ShopEasySA is ready.' as Status;