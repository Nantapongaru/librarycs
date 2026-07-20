<?php
include 'connect.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT image_url, description FROM images"; // ดึงข้อมูล image_url และ description
$result = $conn->query($sql);

$images = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Slideshow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-item img {
            object-fit: contain;  /* ทำให้รูปภาพแสดงครบทั้งหมด */
            width: 100%;           /* ขยายรูปภาพให้เต็มความกว้าง */
            max-height: 650px;     /* เพิ่มความสูงสูงสุดของรูปภาพ */
            height: auto;          /* ปรับความสูงอัตโนมัติตามอัตราส่วน */
        }

        .carousel-caption {
            position: absolute;
            bottom: 30px;          /* กำหนดตำแหน่งของคำบรรยายให้ห่างจากด้านล่าง */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.6);  /* เพิ่มพื้นหลังโปร่งใส */
            color: white;
            padding: 15px;
            border-radius: 10px;  /* เพิ่มมุมมนให้กับกล่องคำบรรยาย */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);  /* เพิ่มเงาให้กับกล่องคำบรรยาย */
            max-width: 80%;        /* กำหนดความกว้างสูงสุดของกล่องคำบรรยาย */
            text-align: center;    /* จัดข้อความให้อยู่กลาง */
        }

        /* เพิ่มการตอบสนองสำหรับมือถือ */
        @media (max-width: 767px) {
            .carousel-caption {
                bottom: 10px; /* ลดระยะห่างในหน้าจอขนาดเล็ก */
                font-size: 14px; /* ลดขนาดฟอนต์ */
                max-width: 90%;  /* ลดความกว้างของกล่องคำบรรยาย */
            }
        }
    </style>
</head>

<body>
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            <?php foreach ($images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars($image['image_url']) ?>" class="d-block w-100" alt="Slide">

                    <!-- คำบรรยาย -->
                    <div class="carousel-caption d-none d-md-block">
                        <p><?= htmlspecialchars($image['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ปุ่มควบคุมการเลื่อน -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
