<?php
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'header_admin.php';
include("connect.php");  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้รับ ID จาก URL หรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลรูปภาพและคำบรรยายจากฐานข้อมูล
    $sql = "SELECT * FROM images WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // ตรวจสอบว่าไม่พบข้อมูล
    if (!$row) {
        echo "<script>alert('ไม่พบข้อมูลรูปภาพ'); window.location = 'admin_img.php';</script>";
        exit();
    }

    // ตรวจสอบการอัปเดตข้อมูล
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $description = mysqli_real_escape_string($conn, $_POST['description']);  // คำบรรยายใหม่

        // ถ้ามีการอัปโหลดรูปภาพใหม่
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image = $_FILES['image'];

            // กำหนด directory ที่จะเก็บไฟล์
            $upload_dir = "img/";

            // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
            if ($image['size'] <= 5000000) {
                // กำหนดชื่อไฟล์ใหม่ที่ไม่ซ้ำกัน
                $image_new_name = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                $image_path = $upload_dir . $image_new_name;

                // ย้ายไฟล์จาก temporary ไปที่ folder ที่ต้องการ
                if (move_uploaded_file($image['tmp_name'], $image_path)) {
                    // ลบไฟล์เก่า (ถ้ามี)
                    unlink($row['image_url']);

                    // อัปเดต URL ของรูปภาพและคำบรรยายในฐานข้อมูล
                    $sql_update = "UPDATE images SET image_url = '$image_path', description = '$description' WHERE id = '$id'";
                    if (mysqli_query($conn, $sql_update)) {
                        echo "<script>alert('แก้ไขข้อมูลรูปภาพสำเร็จ'); window.location = 'admin_img.php';</script>";
                    } else {
                        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
                    }
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');</script>";
                }
            } else {
                echo "<script>alert('ขนาดไฟล์ใหญ่เกินไป (ต้องไม่เกิน 5MB)');</script>";
            }
        } else {
            // อัปเดตคำบรรยายถ้าไม่อัปโหลดรูปใหม่
            $sql_update = "UPDATE images SET description = '$description' WHERE id = '$id'";
            if (mysqli_query($conn, $sql_update)) {
                echo "<script>alert('แก้ไขคำบรรยายรูปภาพสำเร็จ'); window.location = 'admin_img.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');</script>";
            }
        }
    }
} else {
    echo "<script>alert('ไม่พบข้อมูล ID'); window.location = 'admin_img.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรูปภาพ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h2 class="text-center mb-4">แก้ไขรูปภาพ</h2>

        <!-- ฟอร์มแก้ไขข้อมูลรูปภาพ -->
        <form action="edit_img.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">เลือกรูปภาพใหม่ (ถ้ามี)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">คำบรรยายรูปภาพ</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="current_image" class="form-label">รูปภาพปัจจุบัน</label><br>
                <img src="<?php echo $row['image_url']; ?>" alt="Current Image" class="img-fluid" style="max-width: 300px;">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                <a href="admin_img.php" class="btn btn-secondary">กลับสู่แกลเลอรี</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
