CREATE DATABASE IF NOT EXISTS sunson;
USE sunson;

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
    'K@TSunShine16'
);

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
    'admin123'
);
