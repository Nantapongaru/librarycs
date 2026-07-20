<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* เพิ่ม CSS ที่นี่ */
        html, body {
            height: 100%; /* ทำให้ความสูงของหน้าเต็ม */
            margin: 0; /* ลบระยะขอบ */
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1; 
        }

        footer {
            background-color: blueviolet; /* หากคุณต้องการสีม่วง */
            color: aliceblue;
            width: 100%;
            height: 50px;
            text-align: center;
            padding-top: 10px;
            margin-top: auto; /* ทำให้ footer อยู่ที่ขอบล่าง */
        }

        /* เพิ่มสไตล์เพื่อยกเลิกการใช้สีพื้นหลังจาก Bootstrap */
        footer.py-3.my-4 {
            background-color: transparent !important; /* ลบสีพื้นหลังของ Bootstrap */
            color: aliceblue !important; /* กำหนดสีตัวอักษร */
        }
    </style>
</head>
<body>

    

    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">หน้าแรก</a></li>
            <!-- <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link px-2 text-body-secondary">Logout</a></li> -->
        </ul>
        <p class="text-center text-body-secondary">สาขาวิทยาการคอมพิวเตอร์</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
