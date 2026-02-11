<?php
// 1. ปิด Error Text ปกติ เพื่อไม่ให้ JSON พัง
error_reporting(E_ALL);
ini_set('display_errors', 0); 

// 2. ตั้งค่า Header เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// 3. เชื่อมต่อฐานข้อมูล
require 'db_connect.php'; 

$response = array();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // รับค่าและตัดช่องว่างซ้ายขวา
        $firstname  = trim($_POST['firstname'] ?? '');
        $lastname   = trim($_POST['lastname'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $birthdate  = !empty($_POST['birthdate']) ? $_POST['birthdate'] : NULL;
        $department = trim($_POST['department'] ?? '');
        $position   = trim($_POST['position'] ?? '');
        $emp_id     = trim($_POST['emp_id'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';

        // --- การตรวจสอบข้อมูล (Validation) ---

        // 1. ตรวจสอบค่าว่าง
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($department)) {
            throw new Exception("กรุณากรอกข้อมูลที่มีดอกจัน (*) ให้ครบถ้วน");
        }

        // 2. ตรวจสอบรูปแบบอีเมล
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("รูปแบบอีเมลไม่ถูกต้อง");
        }

        // 3. ตรวจสอบความยาวรหัสผ่าน
        if (strlen($password) < 8) {
            throw new Exception("รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร");
        }

        // --- เตรียมบันทึกข้อมูล ---

        // เข้ารหัสรหัสผ่าน
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employees (firstname, lastname, phone, birthdate, department, position, emp_id, email, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // ผูกตัวแปร (9 ตัว)
            $stmt->bind_param("sssssssss", $firstname, $lastname, $phone, $birthdate, $department, $position, $emp_id, $email, $password_hashed);

            if ($stmt->execute()) {
                $response = [
                    "status" => "success", 
                    "message" => "ลงทะเบียนสำเร็จ! กำลังนำทางไปหน้าเข้าสู่ระบบ...",
                ];
            } else {
                // เช็คกรณีข้อมูลซ้ำ (Duplicate Entry)
                if ($conn->errno == 1062) {
                     throw new Exception("อีเมลนี้ถูกใช้งานไปแล้ว");
                } else {
                     throw new Exception("Database Error: " . $stmt->error);
                }
            }
            $stmt->close();
        } else {
            throw new Exception("Prepare Failed: " . $conn->error);
        }

    } else {
        throw new Exception("Invalid Request Method");
    }

} catch (Exception $e) {
    // ส่ง Error กลับไป
    $response = ["status" => "error", "message" => $e->getMessage()];
}

// ปิดการเชื่อมต่อ
if(isset($conn)) $conn->close();

// ส่งค่ากลับเป็น JSON
echo json_encode($response);
?>