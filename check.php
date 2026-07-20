<?php
session_start();
include "connect.php";  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้รับข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['user_m']) && isset($_POST['pass_m']) && isset($_POST['user_type'])) {
    $user_m = $_POST['user_m'];
    $pass_m = $_POST['pass_m'];
    $user_type = $_POST['user_type'];  // รับค่าประเภทผู้ใช้งาน

    // ตรวจสอบว่าเลือกเป็นสมาชิกหรือผู้ดูแลระบบ
    if ($user_type == 'member') {
        // ตรวจสอบผู้ใช้ในตารางสมาชิก
        $a = mysqli_query($conn, "SELECT * FROM member WHERE user_m='$user_m'");
        $s = mysqli_fetch_array($a);
        $rows = mysqli_num_rows($a);

        // ตรวจสอบว่ามีผู้ใช้หรือไม่
        if ($rows) {
            // echo "Found member<br>";
            // ตรวจสอบรหัสผ่านแบบธรรมดา
            if ($pass_m == $s['pass_m']) {
                $_SESSION['id_m'] = $s['id_m'];
                $_SESSION['ses_username'] = $user_m;  // ใช้ $user_m แทน $_POST['user_m'] เพื่อหลีกเลี่ยงการใช้ค่าเดิม
                $_SESSION['status_m'] = $s['status_m'];
                echo "<meta http-equiv=\"refresh\" content=\"1;URL=index-mem.php\">";
            } else {
                echo "Incorrect password for member<br>";
                echo "<meta http-equiv=\"refresh\" content=\"2;URL=login.php\">";
            }
        } else {
            echo "User not found<br>";
            echo "<meta http-equiv=\"refresh\" content=\"2;URL=login.php\">";
        }
    } elseif ($user_type == 'admin') {
        // ตรวจสอบผู้ดูแลระบบในตาราง admin
        $a = mysqli_query($conn, "SELECT * FROM admin WHERE admin_user='$user_m'");
        $s = mysqli_fetch_array($a);
        $rows = mysqli_num_rows($a);

        // ตรวจสอบว่ามีผู้ดูแลระบบหรือไม่
        if ($rows) {
            // echo "Found admin<br>";
            // ตรวจสอบรหัสผ่านแบบธรรมดา
            if ($pass_m == $s['admin_password']) {
                $_SESSION['admin_id'] = $s['admin_id'];
                $_SESSION['admin_name'] = $s['admin_name'];
                $_SESSION['admin_user'] = $user_m;  // ใช้ $user_m แทน $_POST['user_m'] เพื่อหลีกเลี่ยงการใช้ค่าเดิม
                echo "<meta http-equiv=\"refresh\" content=\"1;URL=admin_das.php\">";
            } else {
                echo "Incorrect password for admin<br>";
                echo "<meta http-equiv=\"refresh\" content=\"2;URL=login.php\">";
            }
        } else {
            echo "Admin not found<br>";
            echo "<meta http-equiv=\"refresh\" content=\"2;URL=login.php\">";
        }
    }
} else {
    // ถ้าไม่ได้รับข้อมูลจากฟอร์ม
    echo "No data received<br>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=login.php\">";
}
?>
