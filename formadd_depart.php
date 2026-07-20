<?php 
session_start();
include 'connect.php';
include 'header_admin.php';

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่าได้ข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_depart = $_POST['id_depart'];
    $status_data = $_POST['status_data'];  // รับค่าสถานะข้อมูลจากฟอร์ม (นักศึกษา/อาจารย์)

    // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO data_depart (id_depart, status_data) VALUES ('$id_depart', '$status_data')";

    // ดำเนินการกับฐานข้อมูล
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('ข้อมูลถูกเพิ่มเรียบร้อย'); window.location='admin_depart.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล'); window.location='admin_depart.php';</script>";
    }
}
?>

<section class="main-content">
<div class="container mt-5">
    <h1 class="text-center mb-4">เพิ่มข้อมูลสมาชิกสาขา</h1>
    <form action="formadd_depart.php" method="POST">
        <div class="form-group">
            <label for="id_depart">รหัสประจำตัว:</label>
            <input type="text" class="form-control" id="id_depart" name="id_depart" required placeholder="รหัสนักศึกษา/ รหัสอาจารย์">
        </div>
        <div class="form-group">
            <label for="status_data">สถานะ:</label>
            <select class="form-control " id="status_data"  name="status_data" required>
                <option value="นักศึกษา">นักศึกษา</option>
                <option value="อาจารย์">อาจารย์</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">เพิ่มข้อมูล</button>
        <a href="admin_depart.php" class="btn btn-secondary mt-2 ">กลับ</a>
    </form>
</div>
</section>
