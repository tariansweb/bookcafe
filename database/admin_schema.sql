-- BookCafe Admin Schema
-- Run this script to add admin functionality

USE bookcafe_db;

-- Add stock column to books table if it doesn't exist
ALTER TABLE books 
ADD COLUMN IF NOT EXISTS stock INT DEFAULT 0 AFTER price;

-- Add created_at to subscribers if not exists
ALTER TABLE subscribers 
CHANGE COLUMN subscribed_at created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin activity log
CREATE TABLE IF NOT EXISTS admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert default admin user (password: admin123 - CHANGE THIS IN PRODUCTION!)
-- Password hash generated with password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admin_users (username, email, password, full_name, role) VALUES
('admin', 'admin@bookcafe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'super_admin');

-- Add indexes for better performance
CREATE INDEX idx_admin_activity_admin ON admin_activity_log(admin_id);
CREATE INDEX idx_admin_activity_created ON admin_activity_log(created_at);
CREATE INDEX idx_contact_messages_read ON contact_messages(is_read);
CREATE INDEX idx_subscribers_active ON subscribers(is_active);


