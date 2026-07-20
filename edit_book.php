<?php
session_start();
include 'header_admin.php';
include "connect.php";

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่าได้ข้อมูล id_b มาหรือไม่
if (isset($_GET['id_b'])) {
    $id_b = $_GET['id_b'];

    // ดึงข้อมูลหนังสือที่ต้องการแก้ไขจากฐานข้อมูล
    $sql = "SELECT * FROM book WHERE id_b = '$id_b'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_array($result);

    if (!$book) {
        echo "<script>alert('ไม่พบข้อมูลหนังสือ'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลรหัสหนังสือ'); window.history.back();</script>";
    exit();
}

// เมื่อส่งข้อมูลมาเพื่ออัพเดท
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_b = $_POST['name_b'];
    $detail_b = $_POST['detail_b'];
    $type_b = $_POST['type_b'];
    $status_b = $_POST['status_b'];

    // จัดการอัปโหลดรูปภาพ
    $pic_b = $book['pic_b']; // ใช้ชื่อภาพเดิมถ้าไม่อัปโหลดใหม่
    if (!empty($_FILES['pic_b']['name'])) {
        $pic_b = "book_" . time() . "_" . basename($_FILES['pic_b']['name']);
        move_uploaded_file($_FILES['pic_b']['tmp_name'], "image/" . $pic_b);
    }

    // อัพเดทข้อมูลหนังสือ
    $sql = "UPDATE book SET name_b = '$name_b', detail_b = '$detail_b', type_b = '$type_b', pic_b = '$pic_b', status_b = '$status_b' WHERE id_b = '$id_b'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('อัพเดทข้อมูลหนังสือสำเร็จ'); window.location='admin_book.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัพเดทข้อมูล'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขหนังสือ</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>แก้ไขข้อมูลหนังสือ</h2>
    <form action="edit_book.php?id_b=<?php echo $id_b; ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">ชื่อหนังสือ:</label>
            <input type="text" name="name_b" class="form-control" value="<?php echo $book['name_b']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อผู้แต่ง:</label>
            <textarea name="detail_b" class="form-control"><?php echo $book['detail_b']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">ประเภทหนังสือ:</label>
            <select name="type_b" class="form-control">
                <?php
                $type_result = mysqli_query($conn, "SELECT * FROM type_book");
                while ($row = mysqli_fetch_array($type_result)) {
                    echo "<option value='{$row['type_id']}'" . ($row['type_id'] == $book['type_b'] ? " selected" : "") . ">{$row['type_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">รูปภาพปัจจุบัน:</label><br>
            <img src="image/<?php echo $book['pic_b']; ?>" width="100" height="100"><br><br>
            <label class="form-label">อัปโหลดรูปภาพใหม่:</label>
            <input type="file" name="pic_b" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">สถานะ:</label>
            <select name="status_b" class="form-control">
                <option value="1" <?php echo ($book['status_b'] == 1 ? 'selected' : ''); ?>>ยืมได้</option>
                <option value="0" <?php echo ($book['status_b'] == 0 ? 'selected' : ''); ?>>ถูกยืมไปแล้ว</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">อัพเดทข้อมูลหนังสือ</button>
        <a href="admin_book.php" class="btn btn-secondary">กลับ</a>
    </form>
</div>
</body>
</html>
