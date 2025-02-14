# Web Dev Finals Project

## Description
This repository contains the code for my Web Dev I Finals project: A website for the company Cebu Best Value Trading

## Installation and Database Setup
1. Clone the repository to htdocs
2. Create a database named "web_dev_finals_project" in phpmyadmin
3. Run the following sql code to create the necessary tables:

users:
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'worker', 'client') NOT NULL
);
