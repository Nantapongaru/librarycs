<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include "connect.php";

// ตรวจสอบว่า keyword ถูกส่งมาจากฟอร์มหรือไม่
if (isset($_POST['keyword'])) {
    // รับค่าคำค้นจากผู้ใช้
    $keyword = $_POST['keyword'];

    // สร้างคำค้น SQL โดยใช้คำค้นจากผู้ใช้ (keyword) แบบง่าย ๆ
    // โดยไม่ทำการป้องกัน SQL Injection
    $sql = "SELECT book.id_b, book.name_b, book.detail_b, type_book.type_name, book.pic_b, book.status_b 
            FROM book 
            INNER JOIN type_book ON book.type_b = type_book.type_id";

    // ส่งคำค้น SQL ไปยังฐานข้อมูล
    $result = mysqli_query($conn, $sql);

    // ใช้ลูปเพื่อดึงข้อมูลแต่ละแถวจากผลลัพธ์ที่ได้จากฐานข้อมูล
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        // แสดงผลลัพธ์แต่ละแถวในรูปแบบ HTML โดยแสดงข้อมูลจากคอลัมน์ที่ดึงมาจากฐานข้อมูล
        echo "<tr>
                <td>{$row['id_b']}</td>    <!-- รหัสหนังสือ -->
                <td>{$row['name_b']}</td>  <!-- ชื่อหนังสือ -->
                <td>{$row['detail_b']}</td>  <!-- รายละเอียดหนังสือ -->
                <td>{$row['type_name']}</td>  <!-- ประเภทหนังสือ -->
                <td><img src='image/{$row['pic_b']}' width='80' height='80'></td>  <!-- รูปภาพหนังสือ -->
                <td>" . ($row['status_b'] == 1 ? "<span class='badge bg-success'>ยืมได้</span>" : "<span class='badge bg-danger'>ถูกยืมไปแล้ว</span>") . "</td>  <!-- สถานะหนังสือ (ยืมได้หรือถูกยืมไปแล้ว) -->
                <td>
                    <a href='edit_book.php?id_b={$row['id_b']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>  <!-- ลิงค์ไปยังหน้าแก้ไข -->
                    <a href='delete_book.php?id_b={$row['id_b']}' class='btn btn-danger btn-sm mt-2' onclick=\"return confirm('คุณแน่ใจหรือไม่ที่จะลบหนังสือนี้?');\">🗑️ ลบ</a>  <!-- ลิงค์ไปยังหน้าลบหนังสือ -->
                </td>
            </tr>";
    }
}
?>
