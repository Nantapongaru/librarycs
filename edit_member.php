
<?php
session_start();

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include "connect.php";
include 'header_admin.php';
$id_m = $_GET['id_m'];
$sql = "SELECT * FROM member WHERE id_m='$id_m'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_m = $_POST['user_m'];
    $name_m = $_POST['name_m'];
    $tel_m = $_POST['tel_m'];
    $status_m = $_POST['status_m'];

    $sql = "UPDATE member SET 
            user_m='$user_m', name_m='$name_m', 
            tel_m='$tel_m', status_m='$status_m' 
            WHERE id_m='$id_m'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location='admin_member.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขสมาชิก</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>✏️ แก้ไขสมาชิก</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" name="user_m" class="form-control" value="<?= $row['user_m']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" name="name_m" class="form-control" value="<?= $row['name_m']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input type="text" name="tel_m" class="form-control" value="<?= $row['tel_m']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ประเภทสมาชิก</label>
                <select name="status_m" class="form-control">
                    <option value="นักศึกษา" <?= ($row['status_m'] == "นักศึกษา") ? "selected" : ""; ?>>นักศึกษา</option>
                    <option value="อาจารย์" <?= ($row['status_m'] == "อาจารย์") ? "selected" : ""; ?>>อาจารย์</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">อัปเดต</button>
            <a href="admin_member.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>
</html>
