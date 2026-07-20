<?php
// เชื่อมต่อกับฐานข้อมูล
include 'connect.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// คำสั่ง SQL เพื่อดึงข้อมูลหนังสือที่มีการยืมมากที่สุด พร้อมข้อมูลจากตาราง `book`
$query = "
    SELECT b.id_b, b.name_b, b.detail_b, b.pic_b, COUNT(br.borrow_id) AS borrow_count
    FROM borrow br
    JOIN book b ON br.id_b = b.id_b
    WHERE br.status_b = 1
    GROUP BY b.id_b
    ORDER BY borrow_count DESC
    LIMIT 5
";

// ดึงข้อมูลจากฐานข้อมูล
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- ลิงก์ไปยัง Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* ตกแต่ง Container ของการ์ด */
        .book-container {
            display: flex;
            flex-wrap: wrap;
            /* ทำให้การ์ดเรียงต่อกันในแถว */
            gap: 15px;
            padding: 20px 0;
            justify-content: center;
            /* จัดการ์ดให้อยู่กลาง */
        }

        /* ตกแต่งการ์ด */
        .book-item {
            width: 200px;
            /* ขนาดของการ์ด */
            flex-shrink: 0;
            /* ป้องกันการย่อขนาดการ์ด */
        }

        /* ปรับขนาดรูป */
        .book-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        /* ปรับสไตล์ของชื่อหนังสือ */
        .book-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        /* ปรับสไตล์ของรายละเอียดหนังสือ */
        .book-detail {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: center;
        }

        /* ปรับสไตล์ของลำดับ */
        .book-rank {
            font-size: 18px;
            font-weight: bold;
            color: black;
            /* เปลี่ยนสีเป็นดำ */
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <!-- หัวข้อของหน้า -->
        <h2 class="text-center mb-4">หนังสือที่นิยมมากที่สุด 5 อันดับ</h2>

        <div class="book-container">
            <?php
            if ($result->num_rows > 0) {
                $rank = 1; // เริ่มต้นลำดับจาก 1
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="book-item">';
                    echo '<p class="book-rank">ลำดับที่ ' . $rank . '</p>';
                    echo '<div class="card h-100 shadow-sm">';
                    echo '<img class="book-image card-img-top" src="image/' . $row["pic_b"] . '" alt="' . $row["name_b"] . '">';
                    echo '<h5 class="book-title">' . $row["name_b"] . '</h5>';
                    echo '<p class="book-detail">' . $row["detail_b"] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    $rank++; // เพิ่มลำดับ
                }
            } else {
                echo "<p class='text-center'>ไม่พบข้อมูล</p>";
            }
            ?>
        </div>

    </div>

    <!-- ลิงก์ไปยัง Bootstrap JS และ Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>

</html>

<?php
// ปิดการเชื่อมต่อ

?>