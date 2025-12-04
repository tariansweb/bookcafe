-- BookCafe Database Schema
-- Run this script to set up the database tables

CREATE DATABASE IF NOT EXISTS bookcafe_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE bookcafe_db;

-- Categories table for books
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category_id INT,
    description TEXT,
    cover_image VARCHAR(255),
    isbn VARCHAR(20),
    price DECIMAL(10,2) DEFAULT 0.00,
    available BOOLEAN DEFAULT TRUE,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Menu categories
CREATE TABLE IF NOT EXISTS menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50),
    display_order INT DEFAULT 0
) ENGINE=InnoDB;

-- Menu items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    available BOOLEAN DEFAULT TRUE,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    image VARCHAR(255),
    max_attendees INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Insert sample data
INSERT INTO categories (name, slug, description) VALUES
('Fiction', 'fiction', 'Novels and stories from imagination'),
('Non-Fiction', 'non-fiction', 'Real-world knowledge and stories'),
('Poetry', 'poetry', 'Verses that touch the soul'),
('Mystery', 'mystery', 'Thrilling tales of suspense'),
('Classic Literature', 'classics', 'Timeless literary masterpieces');

INSERT INTO menu_categories (name, slug, icon, display_order) VALUES
('Coffee', 'coffee', '☕', 1),
('Tea', 'tea', '🍵', 2),
('Pastries', 'pastries', '🥐', 3),
('Light Bites', 'light-bites', '🥪', 4);

INSERT INTO menu_items (category_id, name, description, price, featured) VALUES
(1, 'House Blend', 'Our signature medium roast with notes of chocolate and caramel', 4.50, TRUE),
(1, 'Espresso', 'Bold and intense single or double shot', 3.50, FALSE),
(1, 'Cappuccino', 'Espresso with steamed milk and velvety foam', 5.00, TRUE),
(1, 'Cold Brew', 'Smooth, slow-steeped coffee served over ice', 5.50, FALSE),
(2, 'Earl Grey', 'Classic bergamot-infused black tea', 4.00, TRUE),
(2, 'Chamomile', 'Calming herbal blend for reading sessions', 4.00, FALSE),
(2, 'Matcha Latte', 'Premium Japanese green tea with steamed milk', 5.50, TRUE),
(3, 'Croissant', 'Buttery, flaky French pastry', 4.50, TRUE),
(3, 'Blueberry Muffin', 'Fresh-baked with wild blueberries', 4.00, FALSE),
(3, 'Chocolate Éclair', 'Choux pastry with cream and chocolate glaze', 5.00, TRUE),
(4, 'Avocado Toast', 'Sourdough with smashed avocado and microgreens', 8.50, TRUE),
(4, 'Caprese Sandwich', 'Fresh mozzarella, tomato, and basil on ciabatta', 9.00, FALSE);

INSERT INTO books (title, author, category_id, description, price, featured) VALUES
('The Midnight Library', 'Matt Haig', 1, 'Between life and death there is a library filled with books that tell the stories of every life you could have lived.', 16.99, TRUE),
('Atomic Habits', 'James Clear', 2, 'An easy and proven way to build good habits and break bad ones.', 18.99, TRUE),
('The Waste Land', 'T.S. Eliot', 3, 'A masterpiece of modernist poetry.', 12.99, FALSE),
('The Silent Patient', 'Alex Michaelides', 4, 'A woman who shot her husband and stopped speaking.', 15.99, TRUE),
('Pride and Prejudice', 'Jane Austen', 5, 'A romantic novel of manners following Elizabeth Bennet.', 14.99, TRUE);

INSERT INTO events (title, description, event_date, event_time, location) VALUES
('Poetry Night', 'Open mic poetry reading with guest performers', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '19:00:00', 'Main Reading Room'),
('Book Club: Fiction Favorites', 'Monthly discussion of contemporary fiction', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '18:30:00', 'Garden Terrace'),
('Author Meet & Greet', 'Meet local authors and get your books signed', DATE_ADD(CURDATE(), INTERVAL 21 DAY), '14:00:00', 'Gallery Space');

