-- Local Database Setup
CREATE DATABASE IF NOT EXISTS training_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE training_db;

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE NULL,
    registration_id VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    address TEXT NOT NULL,
    qualification VARCHAR(100) NOT NULL,
    percentage VARCHAR(20) NOT NULL,
    college VARCHAR(255) DEFAULT NULL,
    year_of_passing VARCHAR(4) DEFAULT NULL,
    program VARCHAR(255) NOT NULL,
    experience TEXT DEFAULT NULL,
    motivation TEXT DEFAULT NULL,
    updates_opt_in TINYINT(1) DEFAULT 0,
    password_hash VARCHAR(255) NULL,
    payment_mode ENUM('ONLINE', 'OFFLINE') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student_id (student_id),
    INDEX idx_email (email),
    INDEX idx_registration_id (registration_id),
    INDEX idx_payment_mode (payment_mode),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id VARCHAR(50) NOT NULL UNIQUE,
    payment_gateway_id VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'INR',
    status ENUM('SUCCESS', 'FAILED', 'PENDING', 'OFFLINE') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registration_id) REFERENCES registrations(registration_id) ON DELETE CASCADE,
    INDEX idx_registration_id (registration_id),
    INDEX idx_payment_gateway_id (payment_gateway_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users Table (Admin & Office Staff)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'office') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin User
-- Password: Admin@123 (CHANGE THIS IMMEDIATELY IN PRODUCTION)
INSERT INTO users (name, email, password_hash, role) VALUES 
('System Administrator', 'admin@institute.com', '$2y$10$2H2o.n1icuaodVL1BFjX2.xSxrUQfRvuHdtVg5NX/pOeD4lYNqoz6', 'admin');

-- Note: The password hash above is for 'Admin@123'
-- Generate new hash using: password_hash('your_password', PASSWORD_DEFAULT)
