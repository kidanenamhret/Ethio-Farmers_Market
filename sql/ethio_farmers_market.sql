-- phpMyAdmin SQL Dump
-- Database: ethio_farmers_market

CREATE DATABASE IF NOT EXISTS ethio_farmers_market;
USE ethio_farmers_market;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer','farmer','admin') DEFAULT 'customer',
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Table: products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    unit VARCHAR(20) DEFAULT 'kg',
    description TEXT,
    image_url VARCHAR(255),
    status ENUM('active','out_of_stock') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table: orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50) DEFAULT 'Cash on Delivery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

-- Table: order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ==========================================
-- Sample Data
-- ==========================================

-- Categories
INSERT INTO categories (name) VALUES
('Vegetables'), ('Fruits'), ('Grains'), ('Dairy'), ('Meat');

-- Users (password = 'password123' hashed with password_hash())
INSERT INTO users (username, full_name, email, phone, password_hash, role, address) VALUES
('admin', 'Admin User', 'admin@ethiofarmers.com', '0911000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Addis Ababa'),
('abebe_farmer', 'Abebe Farmer', 'abebe@farm.com', '0911222333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Debre Zeit'),
('tigist', 'Tigist Buyer', 'tigist@buyer.com', '0913444555', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Bole, Addis');

-- Products (farmer_id 2 = Abebe)
INSERT INTO products (farmer_id, category_id, name, price, stock_quantity, unit, description, image_url, status) VALUES
(2, 1, 'Fresh Tomato', 45.00, 100, 'kg', 'Grown in the volcanic soil of Debre Zeit, these hand-picked tomatoes are known for their deep red color and firm texture. Perfect for fresh salads, Shiro Wot, or making your own traditional tomato paste. Guaranteed organic and pesticide-free.', 'assets/images/products/tomato.png', 'active'),
(2, 1, 'Ethiopian Cabbage (Tikur Gomen)', 30.00, 80, 'kg', 'Our Tikur Gomen is harvested daily to ensure maximum crispness and nutritional value. Rich in vitamins and iron, it is an essential ingredient for a authentic Gomen Wot. These dark, flavorful leaves are a staple of the Ethiopian highland diet.', 'assets/images/products/cabbage.png', 'active'),
(2, 2, 'Mango', 120.00, 50, 'kg', 'Sourced directly from the tropical orchards of Bishoftu, our mangoes are incredibly sweet and fiber-less. They are picked at the peak of ripeness to ensure that signature buttery texture and heavenly aroma. A true taste of the Ethiopian summer.', 'assets/images/products/mango.png', 'active'),
(2, 3, 'Teff Grain', 95.00, 200, 'kg', 'The super-grain of Ethiopia. This premium White Teff is gluten-free and packed with protein and calcium. Milled using traditional methods to maintain its high nutritional profile, it produces the lightest, most flexible Injera with the perfect tang.', 'assets/images/products/teff.png', 'active'),
(2, 4, 'Fresh Milk', 70.00, 30, 'liter', 'Whole, unpasteurized milk from grass-fed cows in the Rift Valley highlands. This creamy, rich milk is perfect for making traditional Ayibe or simply enjoyed fresh. No additives, no preservatives—just pure, wholesome dairy goodness.', 'assets/images/products/milk.png', 'active');

-- Sample Order (customer_id 3)
INSERT INTO orders (customer_id, total_amount, status, shipping_address, payment_method) VALUES
(3, 165.00, 'delivered', 'Bole, Addis Ababa', 'Cash on Delivery');
INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES
(1, 1, 2, 45.00),
(1, 3, 1, 120.00);