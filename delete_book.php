<?php
session_start();
include 'header_admin.php';
include "connect.php";

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่าได้ข้อมูล id_b มาหรือไม่
if (isset($_GET['id_b'])) {
    $id_b = $_GET['id_b'];

    // ดึงข้อมูลหนังสือที่ต้องการลบจากฐานข้อมูล
    $sql = "SELECT pic_b FROM book WHERE id_b = '$id_b'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_array($result);

    if (!$book) {
        echo "<script>alert('ไม่พบข้อมูลหนังสือ'); window.history.back();</script>";
        exit();
    }

    // ลบข้อมูลหนังสือจากฐานข้อมูล
    $delete_sql = "DELETE FROM book WHERE id_b = '$id_b'";
    if (mysqli_query($conn, $delete_sql)) {
        // ลบไฟล์รูปภาพที่เกี่ยวข้อง
        if (file_exists("image/" . $book['pic_b'])) {
            unlink("image/" . $book['pic_b']);
        }
        echo "<script>alert('ลบหนังสือสำเร็จ'); window.location='admin_book.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลรหัสหนังสือ'); window.history.back();</script>";
    exit();
}
?>
