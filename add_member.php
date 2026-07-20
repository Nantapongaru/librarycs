<?php
session_start();
include 'header_admin.php';

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $id_m = $_POST['id_m'];
    $user_m = $_POST['user_m'];
    $pass_m = $_POST['pass_m'];
    $name_m = $_POST['name_m'];
    $tel_m = $_POST['tel_m'];
    $status_m = $_POST['status_m'];

    // สร้างคำสั่ง SQL สำหรับเพิ่มสมาชิก
    $sql = "INSERT INTO member (id_m, user_m, pass_m, name_m,  tel_m, status_m) 
            VALUES ('$id_m', '$user_m', '$pass_m', '$name_m',  '$tel_m', '$status_m')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('เพิ่มสมาชิกเรียบร้อย!'); window.location='admin_member.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มสมาชิก</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2>➕ เพิ่มสมาชิก</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_m" class="form-label">รหัสประจำตัว</label>
                <input type="text" class="form-control" name="id_m" id="id_m" required
                    placeholder="รหัสนักศึกษา/รหัสอาจารย์">
            </div>
            <div class="mb-3">
                <label for="name_m" class="form-label">ชื่อ-สกุล</label>
                <input type="text" class="form-control" name="name_m" id="name_m" placeholder="ชื่อ-สกุล">
            </div>
            <div class="mb-3">
                <label for="tel_m" class="form-label">เบอร์โทร</label>
                <input type="text" class="form-control" name="tel_m" id="tel_m" maxlength="10" placeholder="xxxxxxxxxx">
            </div>
            <div class="mb-3">
                <label for="user_m" class="form-label">Username</label>
                <input type="text" class="form-control" name="user_m" id="user_m" placeholder="Username">
            </div>
            <div class="mb-3">
                <label for="pass_m" class="form-label">Password</label>
                <input type="password" class="form-control" name="pass_m" id="pass_m" placeholder="Password">
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทสมาชิก</label>
                <select name="status_m" class="form-control">
                    <option value="นักศึกษา">นักศึกษา</option>
                    <option value="อาจารย์">อาจารย์</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">บันทึก</button>
            <a href="admin_member.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>

</html>