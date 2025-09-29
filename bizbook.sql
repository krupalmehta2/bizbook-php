-- 1. Create Database
CREATE DATABASE IF NOT EXISTS bizbook 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;
USE bizbook;

-- 2. Users Table (Owners + Customers + Super Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact VARCHAR(20),
    profile_pic VARCHAR(255),
    role ENUM('owner','customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email)
) ENGINE=InnoDB;

-- 3. Businesses Table
CREATE TABLE businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    type ENUM('order','appointment','table') NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(user_id)
) ENGINE=InnoDB;

-- 4. Products (for order-based businesses)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(business_id) REFERENCES businesses(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(business_id)
) ENGINE=InnoDB;

-- 5. Services (for appointment-based businesses)
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    duration INT, -- in minutes
    price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(business_id) REFERENCES businesses(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(business_id)
) ENGINE=InnoDB;

-- 6. Tables (for restaurants/cafes)
CREATE TABLE tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    table_number INT NOT NULL,
    capacity INT DEFAULT 2,
    status ENUM('available','reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(business_id) REFERENCES businesses(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(business_id)
) ENGINE=InnoDB;

-- 7. Bookings / Orders
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,          -- customer
    business_id INT NOT NULL,
    type ENUM('product','service','table') NOT NULL,
    item_id INT NOT NULL,          -- product/service/table id
    quantity INT DEFAULT 1,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    booking_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(business_id) REFERENCES businesses(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(user_id),
    INDEX(business_id)
) ENGINE=InnoDB;

-- 8. Reviews (for feedback)
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(business_id) REFERENCES businesses(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX(business_id),
    INDEX(user_id)
) ENGINE=InnoDB;

-- 9. Insert Default Admin User (example)
INSERT INTO users (name, email, password, role)
VALUES ('Super Admin', 'admin@bizbook.com', MD5('admin123'), 'admin');
