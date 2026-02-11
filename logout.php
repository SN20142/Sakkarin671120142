<?php
session_start();

// ลบ session
session_destroy();

// เปลี่ยนกลับไปหน้า login
header('Location: login.html');
exit;
?>
