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

    // ลบข้อมูลจากฐานข้อมูล
    $sql = "DELETE FROM data_depart WHERE id_data = '$id_data'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('ข้อมูลแผนกถูกลบเรียบร้อยแล้ว'); window.location='admin_depart.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล'); window.history.back();</script>";
    }
}
?>
