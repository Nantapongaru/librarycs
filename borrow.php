<?php
session_start(); // เริ่ม session
include("connect.php");

if (!isset($_SESSION['ses_username'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการยืมหนังสือ'); window.location='login.php';</script>";
    exit();
}

$id_m = $_SESSION['id_m']; 
$status_m = $_SESSION['status_m']; // สถานะ: อาจารย์ / นักศึกษา
$id_b = $_GET['id_b'] ?? ''; // รับค่ารหัสหนังสือจาก URL

// ตรวจสอบข้อมูลหนังสือ
$sql = "SELECT * FROM book WHERE id_b = '$id_b' AND status_b = 1";
$book = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if (!$book) {
    echo "<script>alert('ไม่พบข้อมูลหนังสือหรือหนังสือถูกยืมไปแล้ว'); window.location='index-mem.php';</script>";
    exit();
}

// ตรวจสอบสิทธิ์การยืมหนังสือ
$max_books = ($status_m === "อาจารย์") ? 10 : (($status_m === "นักศึกษา") ? 5 : 0);
if ($max_books == 0) {
    echo "<script>alert('คุณไม่มีสิทธิ์ยืมหนังสือ'); window.location='index-mem.php';</script>";
    exit();
}

// ตรวจสอบจำนวนหนังสือที่ยืม
$sql_borrowed_books = "SELECT COUNT(*) AS total FROM borrow WHERE id_m = '$id_m' AND status_b = 0"; // เพิ่มเงื่อนไข status_b = 0
$borrowed_books = mysqli_fetch_array(mysqli_query($conn, $sql_borrowed_books));
if ($borrowed_books['total'] >= $max_books) {
    echo "<script>alert('คุณยืมหนังสือถึงจำนวนสูงสุดแล้ว'); window.location='borrowed_books.php';</script>";
    exit();
}

// คำนวณวันกำหนดคืน
$days = ($status_m === "อาจารย์") ? 60 : 15;
$date_bor = date("Y-m-d");
$date_re = date("Y-m-d", strtotime("+$days days"));

// บันทึกข้อมูลการยืม
$sql_borrow = "INSERT INTO borrow (id_m, id_b, date_bor, date_re, money, status_b) VALUES ('$id_m', '$id_b', '$date_bor', '$date_re', 0, 0)";
if (mysqli_query($conn, $sql_borrow)) {
    // อัปเดตสถานะหนังสือ
    mysqli_query($conn, "UPDATE book SET status_b = 0 WHERE id_b = '$id_b'");
    echo "<script>alert('ยืมหนังสือเรียบร้อย กำหนดคืน: $date_re'); window.location='b-all.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการยืมหนังสือ กรุณาลองใหม่'); window.location='index-mem.php';</script>";
}
?>
