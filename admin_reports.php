<?php
session_start();
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
    <title>📊 รายงานห้องสมุด</title>
    
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">📊 รายงานสถิติห้องสมุด</h2>
        <?php include 'ferquancy_bor_admin.php'?>
        <h4 class="mt-4">📖 สถิติการยืมหนังสือ</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>เดือน</th>
                    <th>จำนวนการยืม</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT MONTHNAME(date_bor) as month, COUNT(*) as count FROM borrow GROUP BY month ORDER BY MONTH(date_bor)");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>{$row['month']}</td><td>{$row['count']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
