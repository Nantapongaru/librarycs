<?php
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'connect.php';
include 'header_admin.php';
?>
<div class="container mt-4">
    <h3 class="mt-4">💰 ตารางค่าปรับ</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ชื่อผู้ยืม</th>
                <th>รหัสหนังสือ</th>
                <th>วันที่เกินกำหนด</th>
                <th>ค่าปรับสะสม</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // สร้าง SQL query
            $sql = "SELECT 
                    m.name_m AS borrower_name, 
                    b.id_b AS book_id, 
                    DATEDIFF(NOW(), br.date_re) AS overdue_days,
                    LEAST(DATEDIFF(NOW(), br.date_re) * 20, 500) AS fine_amount
                FROM borrow br
                JOIN member m ON br.id_m = m.id_m
                JOIN book b ON br.id_b = b.id_b
                WHERE br.date_re < NOW() AND br.status_b = 'not_returned'
                ORDER BY overdue_days DESC";

            // รัน SQL query
            $result = mysqli_query($conn, $sql);

            // ตรวจสอบว่า SQL query ทำงานได้สำเร็จหรือไม่
            if (!$result) {
                // แสดงข้อความข้อผิดพลาดถ้าเกิดปัญหาในการ query
                echo "Error: " . mysqli_error($conn);
            } else {
                // ตรวจสอบผลลัพธ์และแสดงข้อมูลในตาราง
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['borrower_name'] . "</td>";
                        echo "<td>" . $row['book_id'] . "</td>";
                        echo "<td>" . $row['overdue_days'] . " วัน</td>";
                        echo "<td>" . $row['fine_amount'] . " บาท</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>ไม่มีข้อมูล</td></tr>";
                }
            }

            // ปิดการเชื่อมต่อ
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>