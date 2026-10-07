CREATE DATABASE IF NOT EXISTS sunson;
USE sunson;

<<<<<<< HEAD
-- 1. Users Table (Handles Customers, Employees, and Admins)
=======
>>>>>>> main
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50) DEFAULT NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    department VARCHAR(50) DEFAULT NULL, 
    role ENUM('Admin', 'Employee', 'Customer') DEFAULT 'Customer',
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<<<<<<< HEAD
-- Insert Katherine Sinagaraw's Admin Account
=======

>>>>>>> main
INSERT INTO users (first_name, last_name, middle_name, birthdate, gender, email, phone_number, address, department, role, username, password) 
VALUES (
    'Katherine', 
    'Sinagaraw', 
    '', 
    '1990-01-01', 
    'Female', 
    'kat@sunsonsolar.com', 
    '09000000000', 
    'Pasig City', 
    'Management',
    'Admin',
    'KittyKat16', 
<<<<<<< HEAD
    'K@TSunShine16'
);

-- Insert Sol Solis's Admin Account
=======
    '$2b$12$pUwwsbdtbfIjDdUHFugmbeaQWEAL2n3m8fETW4BSj69W1.8Unmkv.'
);


>>>>>>> main
INSERT INTO users (first_name, last_name, middle_name, birthdate, gender, email, phone_number, address, department, role, username, password) 
VALUES (
    'Sol', 
    'Solis', 
    '', 
    '1990-01-01', 
    'Male', 
    'sol@sunsonsolar.com', 
    '09000000000', 
    'Pasig City', 
    'IT',
    'Admin',
    'admin', 
<<<<<<< HEAD
    'admin123'
);

-- 2. Attendance Table (For Technicians' Site Time-in)
CREATE TABLE attendance_logs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    technician_name VARCHAR(100) NOT NULL,
    time_in DATETIME DEFAULT CURRENT_TIMESTAMP,
    coordinates VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
=======
    '$2b$12$IxQRkpJ.T0q8/ORoP.W8CucYJobBIN3J4FCM.Xrn1NbubdafGGoAG'
>>>>>>> main
);
