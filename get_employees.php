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

    // ดึงข้อมูลพนักงานทั้งหมด
    $sql = "SELECT id, emp_id, firstname, lastname, email, phone, birthdate, department, position FROM employees ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database Error: " . $conn->error);
    }

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    $response = [
        'status' => 'success',
        'data' => $employees
    ];

} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

if(isset($conn)) $conn->close();
echo json_encode($response);
?>
