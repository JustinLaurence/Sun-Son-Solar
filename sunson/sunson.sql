CREATE DATABASE sunson;
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
    'Olap', 
    '1990-07-01', 
    'Female', 
    'katherine.sinagaraw@sunsonsolar.com', 
    '09291230983', 
    'Pasig City', 
    'Administration',
    'Admin',
    'KittyKat16', 
    '$2b$12$pUwwsbdtbfIjDdUHFugmbeaQWEAL2n3m8fETW4BSj69W1.8Unmkv.'
);


INSERT INTO users (first_name, last_name, middle_name, birthdate, gender, email, phone_number, address, department, role, username, password) 
VALUES (
    'Sol', 
    'Solis', 
    'Sun', 
    '1967-01-08', 
    'Male', 
    'sol.solis@sunsonsolar.com', 
    '09000000000', 
    'Pasig City', 
    'IT',
    'Admin',
    'admin', 
    '$2b$12$IxQRkpJ.T0q8/ORoP.W8CucYJobBIN3J4FCM.Xrn1NbubdafGGoAG'
);