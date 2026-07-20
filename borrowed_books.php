<?php
session_start();
include("connect.php");
include 'header1.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['ses_username'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location='login.php';</script>";
    exit();
}

// รับข้อมูลจาก session
$id_m = $_SESSION['id_m'];  // id ของสมาชิก

// ดึงข้อมูลหนังสือที่ผู้ใช้ยืมจากฐานข้อมูล (เฉพาะสถานะยืมอยู่)
$sql = "SELECT b.name_b, br.borrow_id, br.date_bor, br.date_re 
        FROM borrow br 
        JOIN book b ON br.id_b = b.id_b 
        WHERE br.id_m = $id_m AND br.status_b = 0"; // เงื่อนไขสถานะยืมอยู่

// ดึงข้อมูลด้วยการใช้ mysqli_query
$result = mysqli_query($conn, $sql);

echo "<section class='main-content'>";
// แสดงข้อมูลหนังสือที่ยืม
if (mysqli_num_rows($result) > 0) {
    
    echo "<h2>หนังสือที่คุณยืม</h2>";

    echo "
    <table class='table table-bordered table-striped '>
            <thead class='table-dark '>
                <tr>
                    <th>ชื่อหนังสือ</th>
                    <th>วันที่ยืม</th>
                    <th>วันที่กำหนดคืน</th>
                    <th>ค่าปรับ</th>
                    <th>การคืน</th>
                </tr>
            </thead>
            <tbody>";

    // ดึงข้อมูลโดยตรงจาก $result
    while ($row = mysqli_fetch_array($result)) {
        // คำนวณค่าปรับ
        $money = 0;
        if (date("Y-m-d") > $row['date_re']) {
            $diff = (strtotime(date("Y-m-d")) - strtotime($row['date_re'])) / (60 * 60 * 24);
            $money = min($diff * 20, 500);
        }

        echo "<tr>
                <td>{$row['name_b']}</td>
                <td>{$row['date_bor']}</td>
                <td>{$row['date_re']}</td>
                <td>{$money}</td>
                <td>
                    <a href='return.php?borrow_id={$row['borrow_id']}' class='btn btn-danger' onclick=\"return confirm('คุณต้องการคืนหนังสือนี้หรือไม่?')\">คืนหนังสือ</a>
                </td>
              </tr>";
    }

    echo "</tbody></table>";
    
} else {
    echo "<p>คุณยังไม่มีหนังสือที่ยืม</p>";
}
echo "</section>";
include 'footer.php';
?>
