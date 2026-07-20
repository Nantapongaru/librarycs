<?php
session_start();
include 'header_admin.php';
include "connect.php";

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_data'])) {
    $id_data = $_GET['id_data'];

    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM data_depart WHERE id_data = '$id_data'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status_data = $_POST['status_data'];

    $sql_update = "UPDATE data_depart SET status_data = '$status_data' WHERE id_data = '$id_data'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('ข้อมูลได้รับการอัปเดตเรียบร้อยแล้ว'); window.location='admin_depart.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลแผนก</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>แก้ไขข้อมูลแผนก</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">รหัสประจำตัว</label>
            <input type="text" name="id_depart" class="form-control" value="<?php echo $row['id_depart']; ?>" >
        </div>
        <div class="mb-3">
            <label class="form-label">สถานะ</label>
            <select name="status_data" class="form-control">
                <option value="นักศึกษา" <?php echo ($row['status_data'] == 'นักศึกษา') ? 'selected' : ''; ?>>นักศึกษา</option>
                <option value="อาจารย์" <?php echo ($row['status_data'] == 'อาจารย์') ? 'selected' : ''; ?>>อาจารย์</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
        <a href="admin_depart.php" class="btn btn-secondary">กลับ</a>
    </form>
</div>
</body>
</html>
