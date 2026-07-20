<?php
$searchKeyword = isset($_POST['keyword']) ? $_POST['keyword'] : ''; // รับค่าคำค้นจากผู้ใช้

// สร้างคำสั่ง SQL (ยังมีความเสี่ยงกับ SQL Injection)
$query = "SELECT id_data, id_depart, status_data 
          FROM data_depart";

// รันคำสั่ง SQL
$result = mysqli_query($conn, $query) or die("เกิดข้อผิดพลาด SQL: " . mysqli_error($conn));

// แสดงผลลัพธ์
while ($row = mysqli_fetch_array($result)) {
    // ใช้เทคนิคการแปลงค่า status_data ให้แสดงเป็น 'นักศึกษา' หรือ 'อาจารย์'
    $status = ($row['status_data'] == 'นักศึกษา') ? 'นักศึกษา' : 'อาจารย์';
    echo "<tr>
            <td>{$row['id_data']}</td>
            <td>{$row['id_depart']}</td>
            <td>{$status}</td>
            <td>
                <a href='edit_depart.php?id_data={$row['id_data']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
                <a href='delete_depart.php?id_data={$row['id_data']}' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบแผนกนี้?\");'>🗑️ ลบ</a>
            </td>
        </tr>";
}

?>
