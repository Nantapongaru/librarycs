<?php
session_start();
include "connect.php";

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['ses_username']) || !isset($_SESSION['id_m'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบ!'); window.location='login.php';</script>";
    exit();
}

$id_m = $_SESSION['id_m']; // ID ของผู้ใช้
$id_borrow = $_GET['borrow_id'] ?? null;

if (!$id_borrow) {
    echo "<script>alert('ไม่พบข้อมูลการยืมหนังสือ!'); window.location='b-all.php';</script>";
    exit();
}

// ตรวจสอบว่าผู้ใช้เป็นเจ้าของการยืมหรือไม่
$sql_check = "SELECT id_b FROM borrow WHERE borrow_id = '$id_borrow' AND id_m = '$id_m' AND status_b = 0";
$result_check = mysqli_query($conn, $sql_check);

if ($row = mysqli_fetch_assoc($result_check)) {
    $id_b = $row['id_b']; // ดึง ID หนังสือ

    // อัปเดตสถานะในตาราง borrow ให้กลายเป็นสถานะคืนแล้ว (สมมุติใช้ 1 เป็นสถานะคืนแล้ว)
    $sql_update_borrow = "UPDATE borrow SET status_b = 1 WHERE borrow_id = '$id_borrow'";
    if (mysqli_query($conn, $sql_update_borrow)) {
        // อัปเดตสถานะของหนังสือให้ยืมได้
        $sql_update_book = "UPDATE book SET status_b = 1 WHERE id_b = '$id_b'";
        if (mysqli_query($conn, $sql_update_book)) {
            echo "<script>alert('คืนหนังสือเรียบร้อยแล้ว!'); window.location='borrowed_books.php';</script>";
        } else {
            echo "<script>alert('คืนหนังสือสำเร็จ แต่เกิดข้อผิดพลาดในการอัปเดตสถานะหนังสือ: " . mysqli_error($conn) . "'); window.location='borrowed_books.php';</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการคืนหนังสือ: " . mysqli_error($conn) . "'); window.location='borrowed_books.php';</script>";
    }
} else {
    echo "<script>alert('คุณไม่ได้เป็นผู้ยืมหนังสือเล่มนี้!'); window.location='borrowed_books.php';</script>";
}
?>
