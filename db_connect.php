<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_system";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password);
$conn->set_charset("utf8mb4");

// เช็คการเชื่อมต่อเซิร์ฟเวอร์
if ($conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// สร้างฐานข้อมูลถ้าไม่มี
$create_db = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($create_db)) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(["status" => "error", "message" => "Create database failed: " . $conn->error]));
}

// เลือกฐานข้อมูล
if (!$conn->select_db($dbname)) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(["status" => "error", "message" => "Select database failed: " . $conn->error]));
}

// สร้างตารางถ้าไม่มี
$create_table = "CREATE TABLE IF NOT EXISTS `employees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `emp_id` VARCHAR(50) UNIQUE,
  `firstname` VARCHAR(100) NOT NULL,
  `lastname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `birthdate` DATE,
  `department` VARCHAR(50),
  `position` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_emp_id` (`emp_id`),
  INDEX `idx_department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!$conn->query($create_table)) {
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(["status" => "error", "message" => "Create table failed: " . $conn->error]));
}

// ** ห้ามมี echo "Connected successfully"; ตรงนี้เด็ดขาด **
?>
