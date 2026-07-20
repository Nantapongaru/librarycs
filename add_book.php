<?php
session_start();
include 'header_admin.php';
include "connect.php";

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_b = $_POST['id_b'];
    $name_b = $_POST['name_b'];
    $detail_b = $_POST['detail_b'];
    $type_b = $_POST['type_b'];
    $status_b = $_POST['status_b'];

    // ตรวจสอบว่า id_b ซ้ำหรือไม่
    $check_id = mysqli_query($conn, "SELECT id_b FROM book WHERE id_b = '$id_b'");
    if (mysqli_num_rows($check_id) > 0) {
        echo "<script>alert('รหัสหนังสือนี้มีอยู่แล้ว กรุณาใช้รหัสอื่น'); window.history.back();</script>";
        exit();
    }

    // จัดการอัปโหลดรูปภาพ
    $pic_b = "";
    if (!empty($_FILES['pic_b']['name'])) {
        $pic_b = "book_" . time() . "_" . basename($_FILES['pic_b']['name']);
        move_uploaded_file($_FILES['pic_b']['tmp_name'], "image/" . $pic_b);
    }

    // สร้างคำสั่ง SQL แบบตรง
    $sql = "INSERT INTO book (id_b, name_b, detail_b, type_b, pic_b, status_b) 
            VALUES ('$id_b', '$name_b', '$detail_b', '$type_b', '$pic_b', '$status_b')";

    // รันคำสั่ง SQL โดยตรง
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('เพิ่มหนังสือสำเร็จ'); window.location='admin_book.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มหนังสือ</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>เพิ่มหนังสือ</h2>
    <form action="add_book.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">รหัสหนังสือ:</label>
            <input type="text" name="id_b" class="form-control" required placeholder="X000">
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อหนังสือ:</label>
            <input type="text" name="name_b" class="form-control" required placeholder="ชื่อหนังสือ">
        </div>
        <div class="mb-3">
            <label class="form-label">ผู้แต่ง:</label>
            <input type="text" name="detail_b" class="form-control" placeholder="ชื่อผู้เเต่ง">
        </div>
        <div class="mb-3">
            <label class="form-label">ประเภทหนังสือ:</label>
            <select name="type_b" class="form-control">
                <?php
                $type_result = mysqli_query($conn, "SELECT * FROM type_book");
                while ($row = mysqli_fetch_array($type_result)) {
                    echo "<option value='{$row['type_id']}'>{$row['type_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">อัปโหลดรูปภาพ:</label>
            <input type="file" name="pic_b" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">สถานะ:</label>
            <select name="status_b" class="form-control">
                <option value="1">ยืมได้</option>
                <option value="0">ถูกยืมไปแล้ว</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">เพิ่มหนังสือ</button>
        <a href="admin_book.php" class="btn btn-secondary">กลับ</a>
    </form>
</div>
</body>
</html>
