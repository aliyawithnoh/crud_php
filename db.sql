-- Database: booking_system
CREATE DATABASE IF NOT EXISTS booking_system;
USE booking_system;

-- ===========================
-- 1. USERS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer'
);

-- Insert default admin
INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$abcdefghijklmnopqrstuv1234567890ABCDEabcdeABCDE12345', 'admin');
-- NOTE: Replace the hashed password later with a real hash.

-- ===========================
-- 2. SERVICES TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0,
    description TEXT
);

-- Sample services
INSERT INTO services (name, price, description) VALUES
('Massage', 999.00, 'Relaxing body massage'),
('Home Service', 1299.00, 'Home visit massage'),
('Therapy', 1499.00, 'Therapeutic treatment session');

-- ===========================
-- 3. BOOKINGS TABLE
-- ===========================
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    status VARCHAR(150) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- ===========================
-- 4. REVIEWS TABLE
-- ===========================

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===========================
-- 5. COUPONS TABLE
-- ===========================

CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255),
    discount_type ENUM('percent','fixed') DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL,
    expiry_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================
-- 6. STAFF SCHEDULES TABLE
-- ===========================

CREATE TABLE IF NOT EXISTS staff_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    day_of_week ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE
);

-- ===========================
-- 7. INVENTORY TABLE
-- ===========================

CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(150) NOT NULL,
    quantity INT DEFAULT 0,
    unit VARCHAR(50),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ===========================
-- 8. GALLERY TABLE
-- ===========================

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO gallery (image_path) VALUES
('https://images2.minutemediacdn.com/image/upload/c_crop,h_1349,w_2400,x_0,y_138/f_auto,q_auto,w_1100/v1628703164/shape/mentalfloss/649273-youtube-rick_astley.jpg'),
('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3wB5tm_xYT_i9c-Q-5_7ADgwJtiE0_NZ0Hw&s');

