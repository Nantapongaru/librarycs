<?php
$searchKeyword = isset($_POST['keyword']) ? $_POST['keyword'] : ''; // รับค่าคำค้นจากผู้ใช้

// สร้างคำสั่ง SQL (ยังมีความเสี่ยงกับ SQL Injection)
$query = "SELECT id_m, user_m, name_m, tel_m, status_m 
          FROM member ";

// รันคำสั่ง SQL
$result = mysqli_query($conn, $query) or die("เกิดข้อผิดพลาด SQL: " . mysqli_error($conn));

// แสดงผลลัพธ์
while ($row = mysqli_fetch_array($result)) {
    $status = $row['status_m'] == 'นักศึกษา' ? "นักศึกษา" : "อาจารย์";
    echo "<tr>
        <td>{$row['id_m']}</td>
        <td>{$row['user_m']}</td>
        <td>{$row['name_m']}</td>
        <td>{$row['tel_m']}</td>
        <td>$status</td>
        <td>
            <a href='edit_member.php?id_m={$row['id_m']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
            <a href='delete_member.php?id_m={$row['id_m']}' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบสมาชิกนี้?\");'>🗑️ ลบ</a>
        </td>
    </tr>";
}
?>
