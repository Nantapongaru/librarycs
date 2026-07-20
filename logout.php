<?php
// เริ่ม session
session_start();

// ลบข้อมูลทั้งหมดใน session
session_unset();

// ทำลาย session
session_destroy();

// ส่งผู้ใช้กลับไปยังหน้า login
echo "<script>alert('คุณได้ออกจากระบบแล้ว'); window.location='login.php';</script>";
exit();
?>
