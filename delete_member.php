<?php
include "connect.php";
$id_m = $_GET['id_m'];

$sql = "DELETE FROM member WHERE id_m='$id_m'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('ลบสมาชิกเรียบร้อย!'); window.location='admin_member.php';</script>";
} else {
    echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
}
?>
