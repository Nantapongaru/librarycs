<?php session_start(); ?>
<?php
include 'connect.php';
include 'header.php';

// รับประเภทที่เลือกจาก URL
$type = $_GET['type'] ?? '1';  // ถ้าไม่มีการเลือกประเภทจะใช้ค่าเริ่มต้นเป็นประเภท 1

// ดึงข้อมูลประเภทหนังสือและชื่อประเภท
$sql_type = "SELECT type_name FROM type_book WHERE type_id = '$type'";
$result_type = mysqli_query($conn, $sql_type);
$type_row = mysqli_fetch_array($result_type); // ใช้ mysqli_fetch_array แทน mysqli_fetch_assoc
$type_name = $type_row[0]; // ดึงข้อมูลจาก array ตามลำดับที่ได้จากคำสั่ง mysqli_fetch_array

// กำหนดจำนวนหนังสือที่จะแสดงต่อหน้า
$record_show = 4;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $record_show;

// คำนวณจำนวนหนังสือทั้งหมด
$sql_total = "SELECT COUNT(*) FROM book WHERE type_b = '$type'";
$result_total = mysqli_query($conn, $sql_total);
$page_total = ceil(mysqli_fetch_row($result_total)[0] / $record_show);

// ดึงข้อมูลหนังสือที่ตรงกับประเภทที่เลือก
$sql_books = "SELECT id_b, name_b, pic_b, type_name, detail_b FROM book 
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
    <title>แสดงหนังสือประเภท: <?php echo $type_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="main-content">
        <div class="container mt-3">
            <h1>หนังสือในหมวด: <?php echo $type_name; ?></h1>

            <!-- Dropdown เลือกประเภทหนังสือ -->
            <form method="get" action="">
                <div class="form-group mb-3">
                    <label for="type">เลือกประเภทหนังสือ:</label>
                    <select name="type" id="type" class="form-control" onchange="this.form.submit()">
                        <?php
                        // ดึงข้อมูลประเภทหนังสือทั้งหมด
                        $result_all_types = mysqli_query($conn, "SELECT type_id, type_name FROM type_book");
                        while ($row_type = mysqli_fetch_array($result_all_types)) {
                            echo "<option value='{$row_type[0]}' " . ($row_type[0] == $type ? 'selected' : '') . ">{$row_type[1]}</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>

            <div class="row">
    <?php
    // แสดงผลหนังสือ
    while ($row = mysqli_fetch_array($result_books)) {
        echo "<div class='col-sm-3 mb-4'>
                <div class='card d-flex flex-column shadow-lg' style='height: 100%;'>
                    <img src='image/{$row['pic_b']}' class='card-img-top' alt='Book Image' width='100%' height='200'>
                    <div class='card-body d-flex flex-column'>
                        <h5 class='card-title'>{$row['name_b']}</h5>
                        <p class='card-text'>
                            <strong>รหัสหนังสือ:</strong> {$row['id_b']}<br>
                            <strong>ประเภทหนังสือ:</strong> {$row['type_name']}<br>
                            <strong>ผู้แต่ง:</strong> {$row['detail_b']}<br>
                        </p>
                    </div>
                </div>
            </div>";
    }
    ?>
</div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page > 1 ? '' : 'disabled'; ?>">
                        <a class="page-link" href="?type=<?= $type; ?>&page=1">First</a>
                    </li>
                    <li class="page-item <?= $page > 1 ? '' : 'disabled'; ?>">
                        <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $page_total; $i++) { ?>
                        <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?type=<?= $type; ?>&page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php } ?>
                    <li class="page-item <?= $page < $page_total ? '' : 'disabled'; ?>">
                        <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <li class="page-item <?= $page < $page_total ? '' : 'disabled'; ?>">
                        <a class="page-link" href="?type=<?= $type; ?>&page=<?= $page_total; ?>">Last</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
// ปิดการเชื่อมต่อ
mysqli_close($conn);
?>
