<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_system";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    // ห้าม echo ข้อความธรรมดา ให้ส่งเป็น JSON error แทน
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// ** ห้ามมี echo "Connected successfully"; ตรงนี้เด็ดขาด **
?>
