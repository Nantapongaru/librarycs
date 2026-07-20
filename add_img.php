<?php
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'header_admin.php';
include("connect.php");  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการอัปโหลดรูปภาพ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['description'])) {
    $image = $_FILES['image'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);  // เก็บคำบรรยายที่กรอก

    // กำหนด directory ที่จะเก็บไฟล์
    $upload_dir = "img/";

    // ตรวจสอบหากไม่มีข้อผิดพลาดในการอัปโหลด
    if ($image['error'] === 0) {
        // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
        if ($image['size'] <= 5000000) {
            // กำหนดชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
            $image_new_name = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            $image_path = $upload_dir . $image_new_name;

            // ย้ายไฟล์จาก temporary ไปที่ folder ที่ต้องการ
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                // บันทึก URL ของรูปภาพและคำบรรยายในฐานข้อมูล
                $sql_insert = "INSERT INTO images (image_url, description) VALUES ('$image_path', '$description')";
                if (mysqli_query($conn, $sql_insert)) {
                    echo "<script>alert('อัปโหลดรูปภาพสำเร็จ'); window.location = 'admin_img.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');</script>";
                }
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');</script>";
            }
        } else {
            echo "<script>alert('ขนาดไฟล์ใหญ่เกินไป (ต้องไม่เกิน 5MB)');</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรูปภาพ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h2 class="text-center mb-4">เพิ่มรูปภาพ</h2>

        <!-- ฟอร์มอัปโหลดรูปภาพ -->
        <form action="add_img.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">เลือกรูปภาพ</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">คำบรรยายรูปภาพ</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">อัปโหลด</button>
                <a href="admin_img.php" class="btn btn-secondary">กลับสู่แกลเลอรี</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
