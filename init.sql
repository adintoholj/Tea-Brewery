-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Teas Table
CREATE TABLE IF NOT EXISTS teas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'default_tea.png'
);

-- Automatic test data 
INSERT INTO teas (name, category, description, price) VALUES
('Classic Chamomile', 'Herbal', 'Calming herbal infusion to help you unwind.', 4.99),
('Imperial Matcha', 'Green', 'Pure, vibrant stone-ground Japanese green tea.', 6.50),
('Earl Grey Supreme', 'Black', 'Traditional black tea infused with premium bergamot oil.', 5.25);