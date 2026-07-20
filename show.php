<?php
session_start();
include 'connect.php';
include 'header1.php';

// รับประเภทที่เลือกจาก URL
$type = isset($_GET['type']) ? $_GET['type'] : '1';  // ถ้าไม่มีการเลือกประเภทจะใช้ค่าเริ่มต้นเป็นประเภท 1

// ดึงข้อมูลประเภทหนังสือและชื่อประเภท
$sql_type = "SELECT type_name FROM type_book WHERE type_id = '$type'";
$result_type = mysqli_query($conn, $sql_type);
$type_row = mysqli_fetch_array($result_type); // ใช้ fetch_array แทน mysqli_fetch_assoc
$type_name = $type_row['type_name'];

// จำนวนหนังสือที่แสดงต่อหน้า
$record_show = 4;

// รับค่าหน้า (page) จาก URL
$page = isset($_GET['page']) ? $_GET['page'] : 1;  // ถ้าไม่มีการเลือกหน้าให้ใช้หน้า 1 เป็นค่าเริ่มต้น

// คำนวณตำแหน่งเริ่มต้นของข้อมูลที่จะแสดง
$offset = ($page - 1) * $record_show;

// คำนวณจำนวนหน้าทั้งหมด
$sql_total = mysqli_query($conn, "SELECT * FROM book WHERE type_b = '$type'");
$row_total = mysqli_num_rows($sql_total);
$page_total = ceil($row_total / $record_show);

// ดึงข้อมูลหนังสือที่ตรงกับประเภทที่เลือก
$sql_books = "SELECT id_b, name_b, pic_b, status_b, type_name, detail_b 
              FROM book
              INNER JOIN type_book ON book.type_b = type_book.type_id
              WHERE type_b = '$type'
              LIMIT $offset, $record_show";
$result_books = mysqli_query($conn, $sql_books);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงหนังสือประเภท: <?php echo ($type_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<section class="main-content">
    <br>
    <h3 style="text-align:center">หนังสือในหมวด: <?php echo ($type_name); ?></h3>
    <br>
    <div class="container">
        <div class="row">
            <?php
            // แสดงผลหนังสือ
            while ($row = mysqli_fetch_array($result_books)) {
                echo "<div class='col-sm-3 mb-4 d-flex'>
                        <div class='card shadow-lg h-100 d-flex flex-column'> <!-- ใช้ h-100 + flex-column -->
                            <img src='image/{$row['pic_b']}' class='card-img-top' alt='Book Image' style='height: 200px; object-fit: cover;'> <!-- ตั้งค่าให้ภาพขนาดเท่ากับการ์ด -->
                            <div class='card-body d-flex flex-column'>
                                <h5 class='card-title'>{$row['name_b']}</h5>
                                <p class='card-text flex-grow-1'>
                                    <strong>รหัสหนังสือ:</strong> {$row['id_b']}<br>
                                    <strong>ประเภทหนังสือ:</strong> {$type_name}<br>
                                    <strong>สถานะ:</strong> " . ($row['status_b'] == 1 ? "ยืมได้" : "ถูกยืมไปแล้ว") . "
                                </p>
                                <div class='mt-auto'>
                                    " . ($row['status_b'] == 1 ? "<a href='borrow.php?id_b={$row['id_b']}'><button type='button' class='btn btn-primary w-100'>ยืม</button></a>" :
                                    "<button type='button' class='btn btn-secondary w-100 disabled'>ถูกยืมไปแล้ว</button>") . "
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <!-- First Page -->
                <li class="page-item <?= $page > 1 ? '' : 'disabled' ?>">
                    <a class="page-link" href="?type=<?= $type; ?>&page=1">First</a>
                </li>
                <!-- Previous Page -->
                <li class="page-item <?= $page > 1 ? '' : 'disabled' ?>">
                    <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $page_total; $i++) { ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?type=<?= $type; ?>&page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php } ?>

                <!-- Next Page -->
                <li class="page-item <?= $page < $page_total ? '' : 'disabled' ?>">
                    <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>

                <!-- Last Page -->
                <li class="page-item <?= $page < $page_total ? '' : 'disabled' ?>">
                    <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page_total; ?>">Last</a>
                </li>
            </ul>
        </nav>
    </div>
</section>
</body>

</html>

<?php
include 'footer.php';
mysqli_close($conn);
?>
