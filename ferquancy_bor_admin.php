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
    <style>
        /* ตกแต่ง Container ของหนังสือ */
        .book-container {
            display: flex;
            flex-wrap: nowrap; /* ไม่ให้บรรทัดใหม่ */
            gap: 15px;
            justify-content: flex-start;
            padding: 20px;
            margin: 0 auto;
            max-width: 1200px;
            overflow-x: auto; /* ทำให้เลื่อนในแนวนอนได้ */
            white-space: nowrap; /* ไม่ให้ข้อความถูกตัด */
            border: 1px solid #ddd; /* เพิ่มเส้นขอบให้มองเห็นกรอบ */
        }

        /* ตกแต่งแต่ละ Item ของหนังสือ */
        .book-item {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 200px; /* ปรับขนาดให้เล็กลง */
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .book-item:hover {
            transform: scale(1.05);
        }

        /* ตกแต่งรูปภาพหนังสือ */
        .book-image {
            width: 100%;
            height: 150px; /* ปรับขนาดของรูปภาพ */
            object-fit: cover;
            border-radius: 8px;
        }

        /* ตกแต่งชื่อหนังสือ */
        .book-title {
            font-size: 16px; /* ลดขนาดตัวอักษร */
            font-weight: bold;
            margin: 15px 0 10px;
            color: #333;
        }

        /* ตกแต่งรายละเอียดหนังสือ */
        .book-detail {
            font-size: 12px; /* ลดขนาดตัวอักษร */
            color: #666;
            margin-bottom: 10px;
        }

        /* ตกแต่งจำนวนครั้งที่ยืม */
        .borrow-count {
            font-size: 14px;
            color: #ff6347; /* ใช้สีแดงสำหรับเน้น */
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php

   echo '<div class="book-container">';
   echo '<h2 class="text-center mt-5">หนังสือที่นิยมมากที่สุด 5 อันดับ</h2>';
   
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="book-item">';
        echo '<img class="book-image" src="image/' . $row["pic_b"] . '" alt="' . $row["name_b"] . '">';
        echo '<h3 class="book-title">' . $row["name_b"] . '</h3>';
        echo '<p class="book-detail">' . $row["detail_b"] . '</p>';
        echo '<p class="borrow-count">จำนวนครั้งที่ยืม: ' . $row["borrow_count"] . '</p>';
        echo '</div>';
    }
} else {
    echo "<p>ไม่พบข้อมูล</p>";
}

echo '</div>';

// ปิดการเชื่อมต่อ

?>

</body>
</html>



