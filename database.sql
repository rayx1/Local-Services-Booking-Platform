CREATE DATABASE IF NOT EXISTS local_services_booking_platform;
USE local_services_booking_platform;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    role ENUM('customer', 'provider', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    location VARCHAR(150) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_services_provider FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    provider_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    address TEXT NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_customer FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_bookings_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    CONSTRAINT fk_bookings_provider FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, phone, address, role) VALUES
('Admin User', 'admin@localservices.test', '$2y$10$V.eggblWGPDkHRd8FMbzieEFdPIcasX.5j23P5cPoB093nzzLJUia', '9000000001', 'Admin Office, City Center', 'admin'),
('Amit Sharma', 'amit.customer@localservices.test', '$2y$10$DWeTtKMwWRnkN23OBtPsFOa1NNxhUBFmRhARKpm9FJlcVp5HUZn5i', '9000000002', '12 Lake View Road, Kolkata', 'customer'),
('Neha Verma', 'neha.customer@localservices.test', '$2y$10$DWeTtKMwWRnkN23OBtPsFOa1NNxhUBFmRhARKpm9FJlcVp5HUZn5i', '9000000003', '44 Green Park, Delhi', 'customer'),
('Rakesh Plumbing Works', 'rakesh.provider@localservices.test', '$2y$10$NMAbwWBVyT6.paAvLqsVp.jgS9hU334tLJSsNjUGeu3K/PVAJOaxq', '9000000004', '7 Pipe Market, Kolkata', 'provider'),
('Spark Electricals', 'spark.provider@localservices.test', '$2y$10$NMAbwWBVyT6.paAvLqsVp.jgS9hU334tLJSsNjUGeu3K/PVAJOaxq', '9000000005', '19 Electric Lane, Delhi', 'provider'),
('Bright Home Services', 'bright.provider@localservices.test', '$2y$10$NMAbwWBVyT6.paAvLqsVp.jgS9hU334tLJSsNjUGeu3K/PVAJOaxq', '9000000006', '55 Service Street, Bengaluru', 'provider');

INSERT INTO categories (name, description) VALUES
('Plumbing', 'Book plumbers for pipe repairs, leak fixing, tap installation, and bathroom maintenance.'),
('Electrical', 'Hire electricians for wiring, lighting repairs, appliance setup, and safety checks.'),
('Cleaning', 'Find trusted cleaners for homes, offices, kitchens, and deep cleaning tasks.'),
('Tutoring', 'Connect with tutors for school subjects, exam preparation, and skill coaching.'),
('Carpentry', 'Book carpenters for furniture work, wood repair, and custom fitting jobs.');

INSERT INTO services (provider_id, category_id, title, description, price, location, status) VALUES
(4, 1, 'Emergency Plumbing Repair', 'Fast plumbing support for leak repairs, blocked drains, and broken taps.', 499.00, 'Kolkata', 'active'),
(4, 1, 'Bathroom Fitting Installation', 'Professional installation of sinks, taps, showers, and bathroom accessories.', 899.00, 'Howrah', 'active'),
(5, 2, 'Home Electrical Repair', 'Electrical troubleshooting for fans, switches, lights, and minor wiring issues.', 599.00, 'Delhi', 'active'),
(5, 2, 'Appliance Installation Service', 'Installation support for geysers, lights, ceiling fans, and small appliances.', 749.00, 'Noida', 'active'),
(6, 3, 'Deep Home Cleaning', 'Complete home cleaning service including bedrooms, kitchen, and bathrooms.', 1299.00, 'Bengaluru', 'active'),
(6, 4, 'Maths and Science Tutoring', 'One-to-one tutoring support for school students in maths and science.', 650.00, 'Bengaluru', 'active'),
(6, 5, 'Furniture Repair and Assembly', 'Repair wooden furniture and assemble tables, beds, and shelves.', 850.00, 'Bengaluru', 'inactive');

INSERT INTO bookings (customer_id, service_id, provider_id, booking_date, booking_time, address, message, status) VALUES
(2, 1, 4, '2026-05-02', '10:00:00', '12 Lake View Road, Kolkata', 'Kitchen sink is leaking badly.', 'pending'),
(3, 3, 5, '2026-05-03', '14:30:00', '44 Green Park, Delhi', 'Need fan and switchboard repair.', 'accepted'),
(2, 5, 6, '2026-05-05', '09:00:00', '12 Lake View Road, Kolkata', 'Looking for weekend deep cleaning.', 'completed');

INSERT INTO contact_messages (name, email, subject, message) VALUES
('Demo Visitor', 'visitor@example.com', 'Partnership Inquiry', 'I would like to know more about listing local services on the platform.');
