<?php
// เริ่ม session และเชื่อมต่อฐานข้อมูล
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include("connect.php");  // เชื่อมต่อฐานข้อมูล
include 'header_admin.php';

// ดึงข้อมูลภาพจากตาราง images
$sql = "SELECT * FROM images";
$result = mysqli_query($conn, $sql);
?>

<div class="container my-4">
    <h2 class="text-center">แกลเลอรีรูปภาพ</h2>

    <!-- ปุ่มเพิ่มรูปภาพ -->
    <div class="d-flex justify-content-between mb-3">
        <a href="add_img.php" class="btn btn-success">➕ เพิ่มรูปภาพ</a>
    </div>

    <!-- ตารางแสดงรูปภาพ -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>รูปภาพ</th>
                <th>รายละเลียด</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // ตรวจสอบว่าในตารางมีข้อมูลหรือไม่
            if (mysqli_num_rows($result) > 0) {
                // ลูปผ่านผลลัพธ์ที่ดึงมาแสดงในตาราง
                $counter = 1;  // ตัวแปรสำหรับนับลำดับ
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";  // ลำดับ
                    // แสดงรูปภาพในตาราง
                    echo "<td><img src='" . $row['image_url'] . "' alt='Image' class='img-fluid' style='max-width: 150px; height: auto;'></td>";
                    echo "<td>" . $row['description'] . "</td>";  // แสดง URL ของรูปภาพ
                    echo "<td>
                            <a href='edit_img.php?id={$row['id']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
                            <a href='delete_img.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบรูปภาพนี้?\");'>🗑️ ลบ</a>
                          </td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='4'>ไม่มีรูปภาพในฐานข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
