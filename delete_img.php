<?php
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include("connect.php");  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการส่ง ID ของรูปภาพ
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลรูปภาพจากฐานข้อมูล
    $sql = "SELECT * FROM images WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // ลบไฟล์ภาพจากระบบ
        if (unlink($row['image_url'])) {
            // ลบข้อมูลในฐานข้อมูล
            $sql_delete = "DELETE FROM images WHERE id = '$id'";
            if (mysqli_query($conn, $sql_delete)) {
                echo "<script>alert('ลบรูปภาพสำเร็จ'); window.location = 'admin_img.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.location = 'admin_img.php';</script>";
            }
        } else {
            echo "<script>alert('ไม่สามารถลบไฟล์ภาพได้'); window.location = 'admin_img.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลรูปภาพที่ต้องการลบ'); window.location = 'admin_img.php';</script>";
    }
} else {
    echo "<script>alert('ไม่พบ ID ของรูปภาพ'); window.location = 'admin_img.php';</script>";
}

mysqli_close($conn);
?>
