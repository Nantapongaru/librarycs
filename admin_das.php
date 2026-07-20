<?php
session_start();

// ตรวจสอบสิทธิ์ผู้ดูแลระบบ
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include "connect.php";
include "header_admin.php";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'test-1.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
        </div>

        <!-- เมนูสำหรับผู้ดูแลระบบ -->
        <div class="list-group mt-4">
            <a href="admin_member.php" class="list-group-item list-group-item-action">👤 จัดการผู้ใช้</a>
            <a href="admin_book.php" class="list-group-item list-group-item-action">📖 จัดการหนังสือ</a>
            <a href="admin_bor.php" class="list-group-item list-group-item-action">📋 รายการยืมหนังสือ</a>
            <a href="admin_fine.php" class="list-group-item list-group-item-action">💰 ค่าปรับ</a>
            <a href="admin_reports.php" class="list-group-item list-group-item-action">📊 รายงาน</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
