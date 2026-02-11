<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        p {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .button-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        a {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: var(--text-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .features {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            text-align: left;
        }

        .features h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 10px 0;
            color: #4b5563;
            display: flex;
            align-items: center;
        }

        .feature-list li:before {
            content: "✓";
            color: var(--primary-color);
            font-weight: bold;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 2rem;
            }

            .button-group {
                flex-direction: column;
            }

            a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏢 HR System</h1>
        <p>ระบบจัดการทรัพยากรบุคคล<br>Human Resources Management System</p>

        <div class="button-group">
            <a href="login.html" class="btn-primary">เข้าสู่ระบบ</a>
            <a href="register.html" class="btn-secondary">ลงทะเบียนใหม่</a>
        </div>

        <div class="features">
            <h3>✨ ฟีเจอร์ของระบบ</h3>
            <ul class="feature-list">
                <li>จัดการข้อมูลพนักงาน</li>
                <li>ลงทะเบียนและเข้าสู่ระบบอย่างปลอดภัย</li>
                <li>แก้ไขและอัปเดตข้อมูล</li>
                <li>ลบข้อมูลพนักงาน</li>
                <li>จัดเก็บข้อมูลในฐานข้อมูล MySQL</li>
                <li>รหัสผ่านเข้ารหัส (Secure Password)</li>
            </ul>
        </div>
    </div>
</body>
</html>
