<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json; charset=utf-8');

require 'db_connect.php';

$response = array();

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }

    http_response_code(200);
    $response = [
        'logged_in' => true,
        'user_id' => $_SESSION['user_id'],
        'email' => $_SESSION['email'],
        'firstname' => $_SESSION['firstname'],
        'lastname' => $_SESSION['lastname']
    ];

} catch (Exception $e) {
    http_response_code(401);
    $response = ['logged_in' => false, 'message' => $e->getMessage()];
}

if(isset($conn)) $conn->close();
echo json_encode($response);
?>
