<?php
// 1. ปิด Error Text ปกติ เพื่อไม่ให้ JSON พัง
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 2. ตั้งค่า Session Cookie สำหรับความปลอดภัย ก่อน session_start()
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => false,  // ถ้าใช้ HTTPS ให้เปลี่ยนเป็น true
    'httponly' => true,  // ป้องกัน JavaScript เข้าถึง cookie
    'samesite' => 'Strict'  // ป้องกัน CSRF
]);

// 3. เริ่ม Session
session_start();

// 4. ตั้งค่า Header เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// 5. เชื่อมต่อฐานข้อมูล
require 'db_connect.php';

$response = array();

try {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid Request Method");
    }

    $email = trim($_POST['email'] ?? '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        throw new Exception("กรุณากรอกอีเมลและรหัสผ่าน");
    }

    // ค้นหาพนักงานจากอีเมล
    $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password FROM employees WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Database Error: Prepare failed");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("อีเมลหรือรหัสผ่านไม่ถูกต้อง");
    }

    $employee = $result->fetch_assoc();
    $stmt->close();

    // ตรวจสอบรหัสผ่าน
    if (!password_verify($password, $employee['password'])) {
        throw new Exception("อีเมลหรือรหัสผ่านไม่ถูกต้อง");
    }

    // Regenerate Session ID หลังการ authentication สำเร็จ (ป้องกัน Session Fixation)
    session_regenerate_id(true);

    // ตั้งค่า session
    $_SESSION['user_id'] = $employee['id'];
    $_SESSION['email'] = $employee['email'];
    $_SESSION['firstname'] = $employee['firstname'];
    $_SESSION['lastname'] = $employee['lastname'];

    $response = [
        "status" => "success",
        "message" => "เข้าสู่ระบบสำเร็จ",
        "user" => [
            "id" => $employee['id'],
            "firstname" => $employee['firstname'],
            "lastname" => $employee['lastname'],
            "email" => $employee['email']
        ]
    ];
} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

if(isset($conn)) $conn->close();
echo json_encode($response);
?>
