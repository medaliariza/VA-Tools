CREATE DATABASE va_enterprise;
USE va_enterprise;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120),
  email VARCHAR(120) UNIQUE,
  password VARCHAR(255),
  role ENUM('admin','gm','hr','employee') DEFAULT 'employee',
  bio TEXT,
  avatar VARCHAR(255)
);

CREATE TABLE todos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  task VARCHAR(255),
  status ENUM('pending','completed') DEFAULT 'pending'
);

CREATE TABLE notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(255),
  content TEXT
);

CREATE TABLE inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120),
  qty INT,
  department VARCHAR(120)
);

CREATE TABLE reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  content TEXT,
  file VARCHAR(255),
  status ENUM('pending','approved') DEFAULT 'pending'
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  message TEXT
);

CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  text VARCHAR(255),
  seen TINYINT DEFAULT 0
);