<?php session_start(); ?>
<?php include 'header.php'; ?>
<?php include "connect.php";

// รับค่าค้นหาจากฟอร์ม
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ตรวจสอบการแบ่งหน้า
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$record_show = 8; // จำนวนหนังสือที่จะแสดงในแต่ละหน้า
$offset = ($page - 1) * $record_show; // คำนวณตำแหน่งการแสดง

// คำนวณจำนวนหนังสือทั้งหมด (พร้อมกับค้นหา)
$sql_total = mysqli_query($conn, "SELECT * FROM book WHERE name_b LIKE '%$search%'");
$row_total = mysqli_num_rows($sql_total);
$page_total = ceil($row_total / $record_show);

// ดึงข้อมูลหนังสือทั้งหมดจากฐานข้อมูล (พร้อมกับค้นหา)
$a = mysqli_query($conn, "SELECT id_b, name_b, pic_b, type_name, detail_b 
                          FROM book 
                          INNER JOIN type_book ON book.type_b = type_book.type_id 
                          WHERE name_b LIKE '%$search%' 
                          LIMIT $offset, $record_show");
?>

<section class="main-content">
    <br>
    <h3 style="text-align:center">ตารางข้อมูลหนังสือทั้งหมด</h3>
    <br>
    <div class="container">
        <!-- ฟอร์มค้นหาหนังสือ -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="<?php echo $search; ?>" placeholder="ค้นหาหนังสือ...">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </div>
        </form>
        
        <div class="row">
        <?php 
        while ($s = mysqli_fetch_array($a)) {
        ?>
            <div class="col-sm-3 mb-4">
                <div class="card d-flex flex-column shadow-lg" style="height: 100%;">
                    <!-- ภาพหนังสือ -->
                    <img src="image/<?php echo $s['pic_b']; ?>" class="card-img-top" alt="Book Image" width="100%" height="200">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $s['name_b']; ?></h5>
                        <p class="card-text">
                            <strong>รหัสหนังสือ:</strong> <?php echo $s['id_b']; ?><br>
                            <strong>ประเภทหนังสือ:</strong> <?php echo $s['type_name']; ?><br>
                            <strong>ผู้แต่ง:</strong> <?php echo $s['detail_b']; ?><br>
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example ">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page > 1 ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=1&search=<?php echo $search; ?>">First</a>
                </li>
                <li class="page-item <?php echo $page > 1 ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $page_total; $i++) { ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php echo $page < $page_total ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item <?php echo $page < $page_total ? '' : 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page_total; ?>&search=<?php echo $search; ?>">Last</a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<?php include "footer.php"; ?>
