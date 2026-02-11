-- สร้างตารางพนักงาน (employees)
CREATE TABLE IF NOT EXISTS `employees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสประจำตัวอ้างอิง',
  `emp_id` VARCHAR(50) UNIQUE COMMENT 'รหัสพนักงาน',
  `firstname` VARCHAR(100) NOT NULL COMMENT 'ชื่อจริง',
  `lastname` VARCHAR(100) NOT NULL COMMENT 'นามสกุล',
  `email` VARCHAR(100) UNIQUE NOT NULL COMMENT 'อีเมล',
  `password` VARCHAR(255) NOT NULL COMMENT 'รหัสผ่าน (เข้ารหัส)',
  `phone` VARCHAR(20) COMMENT 'เบอร์โทรศัพท์',
  `birthdate` DATE COMMENT 'วันเกิด',
  `department` VARCHAR(50) COMMENT 'แผนก/ฝ่าย',
  `position` VARCHAR(100) COMMENT 'ตำแหน่ง',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไข',
  INDEX `idx_email` (`email`),
  INDEX `idx_emp_id` (`emp_id`),
  INDEX `idx_department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ข้อมูลพนักงาน';
