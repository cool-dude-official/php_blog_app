CREATE DATABASE IF NOT EXISTS blog_app;
USE blog_app;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
email VARCHAR(150) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('admin', 'contributor') DEFAULT 'contributor'
);

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL
);

CREATE TABLE posts (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
content TEXT NOT NULL,
category_id INT,
user_id INT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (category_id) REFERENCES categories(id),
FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO categories (name) VALUES ('Tech'), ('Lifestyle'), ('News');
INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@gmail.com', MD5('admin123'), 'admin');
