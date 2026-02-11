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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id === 0) {
        throw new Exception("ไม่พบรหัสพนักงาน");
    }

    // ลบข้อมูล
    if ($stmt = $conn->prepare("DELETE FROM employees WHERE id = ?")) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                $response = ["status" => "success", "message" => "ลบข้อมูลสำเร็จ"];
            } else {
                throw new Exception("ไม่พบข้อมูลพนักงาน");
            }
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
