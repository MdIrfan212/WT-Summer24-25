CREATE DATABASE IF NOT EXISTS grocery_management;
USE grocery_management;

--Products Table
CREATE TABLE IF NOT EXISTS products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT nULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    created_by VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--orders Table
CREATE TABLE IF NOT EXISTS orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(100) NOT nULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('PENDING','DEIVERED') DEFAULT 'pending'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivered_at DATETIME NULL
);

-- Complaints table
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open','closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--Insert sample data
INSERT INTO produicts (name, category,price,stock,description,created_by)
VALUES
('Rice', 'Grains', 50.00, 100, 'Premium quality rice', 'Admin'),
('Milk', 'Dairy', 70.00, 50, 'Fresh cow milk', 'Admin');

INSERT INTO orders (customer, total, status)
VALUES 
('Rahim', 200.00, 'pending'),
('Karim', 150.00, 'pending');

INSERT INTO complaints (customer, message, status)
VALUES 
('Jamal', 'The delivery was late.', 'open');