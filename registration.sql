-- -----------------------------------------------
-- Database: registration_db
-- -----------------------------------------------

-- Create database
CREATE DATABASE IF NOT EXISTS registration_db;
USE registration_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    dob DATE,
    gender VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    hobbies VARCHAR(255),
    linkedin VARCHAR(255),
    github VARCHAR(255),
    profile_image VARCHAR(255),
    resume_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional: Insert a test user
INSERT INTO users (full_name, email, password, phone, dob, gender, address, city, state, country, hobbies, linkedin, github, profile_image, resume_file)
VALUES ('Test User', 'test@example.com', '$2y$10$abcdefghijklmnopqrstuv', '1234567890', '2000-01-01', 'Other', '123 Street', 'City', 'State', 'Country', 'Reading, Coding', 'https://linkedin.com/in/test', 'https://github.com/test', 'profile.jpg', 'resume.pdf');
