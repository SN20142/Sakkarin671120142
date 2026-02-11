<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json; charset=utf-8');

require 'db_connect.php';

$response = array();

try {
    // ตรวจสอบการ login
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("กรุณาเข้าสู่ระบบก่อน");
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id === 0) {
        throw new Exception("ไม่พบรหัสพนักงาน");
    }

    // ดึงข้อมูล
    $stmt = $conn->prepare("SELECT id, emp_id, firstname, lastname, email, phone, birthdate, department, position FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("ไม่พบข้อมูลพนักงาน");
    }

    $employee = $result->fetch_assoc();
    $stmt->close();

    http_response_code(200);
    $response = [
        'status' => 'success',
        'data' => $employee
    ];

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

if(isset($conn)) $conn->close();
echo json_encode($response);
?>
