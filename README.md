# 🏨 Hostel Management System (HMS)

A full-stack web-based Hostel Management System built using PHP, MySQL, HTML, CSS, and Bootstrap. This system provides role-based access for Admin, Warden, and Students to manage hostel operations efficiently.

## 🚀 Features

### 👨‍💼 Admin
- Add and manage rooms
- Assign students to rooms
- Track student payments
- View reports (room occupancy & payments)

### 🧑‍🎓 Student
- View assigned room details
- View payment history
- Submit maintenance requests
- Track maintenance status

### 🧑‍🔧 Warden
- View all room assignments
- Monitor hostel occupancy
- Manage maintenance requests (mark resolved)
- View student details

## 🛠️ Tech Stack
- Frontend: HTML, CSS, Bootstrap  
- Backend: PHP  
- Database: MySQL  
- Server: XAMPP / Apache  

## 📂 Project Structure

hms/

│
├── admin_dashboard.php
├── student_dashboard.php
├── warden_dashboard.php
├── login.html
├── register.html
├── submit_login.php
├── submit_registration.php
├── db.php
├── style.css
├── add_room.php
├── assign_room.php
├── add_payment.php
├── submit_maintenance.php
├── update_status.php

## 🗄️ Database Setup

Create a database named: hms

Run the following SQL queries:

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    room_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','warden'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10),
    capacity INT,
    occupied INT DEFAULT 0
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    amount DECIMAL(10,2),
    payment_date DATE
);

CREATE TABLE maintenance_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    description TEXT,
    status ENUM('Pending','Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

## ⚙️ Setup Instructions

1. Install XAMPP  
2. Place project in: C:\xampp\htdocs\hms  
3. Start Apache and MySQL  
4. Open http://localhost/phpmyadmin  
5. Create database "hms" and run SQL queries  
6. Run project: http://localhost/hms/register.html  

## 🔐 Application Flow

1. Register user (Student / Admin / Warden)  
2. Login  
3. Redirect based on role:  
   - Admin → Admin Dashboard  
   - Student → Student Dashboard  
   - Warden → Warden Dashboard  

## 🔮 Future Improvements
- Email verification  
- Payment gateway integration  
- Room auto-allocation  
- Analytics dashboard  
- Better UI/UX  

## 👨‍💻 Author
Developed by 
Anshpal Rathore

## ⭐ Support
If you like this project, give it a ⭐ on GitHub!
