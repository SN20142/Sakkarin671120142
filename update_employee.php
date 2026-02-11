<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db_connect.php';

$response = array();

try {
    // ตรวจสอบการ login
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("กรุณาเข้าสู่ระบบก่อน");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $firstname = trim($_POST['firstname'] ?? null);
    $lastname = trim($_POST['lastname'] ?? null);
    $email = trim($_POST['email'] ?? null);
    $phone = trim($_POST['phone'] ?? null);
    $birthdate = !empty($_POST['birthdate']) ? trim($_POST['birthdate']) : NULL;
    $department = trim($_POST['department'] ?? null);
    $position = trim($_POST['position'] ?? null);
    $emp_id = trim($_POST['emp_id'] ?? null);

    if ($id === 0 || empty($firstname) || empty($lastname) || empty($email)) {
        throw new Exception("กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน");
    }

    // ตรวจสอบรูปแบบอีเมล
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("รูปแบบอีเมลไม่ถูกต้อง");
    }

    // ตรวจสอบว่าอีเมลซ้ำกันหรือไม่ (ยกเว้นตัวเอง)
    if ($check_email = $conn->prepare("SELECT id FROM employees WHERE email = ? AND id != ?")) {
        $check_email->bind_param("si", $email, $id);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $check_email->close();
            throw new Exception("อีเมลนี้ถูกใช้แล้ว");
        }
        $check_email->close();
    } else {
        throw new Exception("Database Error (email check): " . $conn->error);
    }

    // อัปเดตข้อมูล
    $sql = "UPDATE employees SET firstname = ?, lastname = ?, email = ?, phone = ?, birthdate = ?, department = ?, position = ?, emp_id = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssi", $firstname, $lastname, $email, $phone, $birthdate, $department, $position, $emp_id, $id);

        if ($stmt->execute()) {
            http_response_code(200);
            $response = ["status" => "success", "message" => "อัปเดตข้อมูลสำเร็จ"];
        } else {
            throw new Exception("Database Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        throw new Exception("Database Error (prepare): " . $conn->error);
    }

} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

if(isset($conn)) $conn->close();
echo json_encode($response);
?>
