<?php
// ปิด Error Text เพื่อไม่ให้ JSON พัง
error_reporting(E_ALL);
ini_set('display_errors', 0); 
header('Content-Type: application/json; charset=utf-8');

$result = [];

try {
    // ทดสอบการเชื่อมต่อ
    $conn = new mysqli("localhost", "root", "", "hr_system");
    $conn->set_charset("utf8mb4");
    
    if ($conn->connect_error) {
        throw new Exception("Connection Error: " . $conn->connect_error);
    }
    
    $result['connection'] = 'SUCCESS';
    
    // ทดสอบว่าตาราอยู่หรือไม่
    $result['table_check'] = 'Checking...';
    
    $check_table = "SHOW TABLES LIKE 'employees'";
    $table_result = $conn->query($check_table);
    
    if ($table_result && $table_result->num_rows > 0) {
        $result['table_exists'] = 'YES - employees table found';
        
        // แสดง structure
        $describe = $conn->query("DESCRIBE employees");
        if ($describe) {
            $result['table_columns'] = [];
            while ($row = $describe->fetch_assoc()) {
                $result['table_columns'][] = $row['Field'] . ' (' . $row['Type'] . ')';
            }
        }
    } else {
        $result['table_exists'] = 'NO - employees table not found';
    }
    
    // ทดสอบ INSERT (ไม่บันทึกจริง)
    $result['test_insert'] = 'Preparing test...';
    
    $test_sql = "INSERT INTO employees (firstname, lastname, phone, birthdate, department, position, emp_id, email, password, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    if ($stmt = $conn->prepare($test_sql)) {
        $result['prepare_success'] = 'YES';
        
        // test bind
        $firstname = "Test";
        $lastname = "User";
        $phone = "0812345678";
        $birthdate = "1990-01-01";
        $department = "IT";
        $position = "Developer";
        $emp_id = "TEST001";
        $email = "test@test.com";
        $password_hashed = password_hash("password123", PASSWORD_DEFAULT);
        
        if ($stmt->bind_param("sssssssss", $firstname, $lastname, $phone, $birthdate, $department, $position, $emp_id, $email, $password_hashed)) {
            $result['bind_success'] = 'YES';
        } else {
            $result['bind_success'] = 'NO - ' . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $result['prepare_success'] = 'NO - ' . $conn->error;
    }
    
    $conn->close();
    
} catch (Exception $e) {
    $result['error'] = $e->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
