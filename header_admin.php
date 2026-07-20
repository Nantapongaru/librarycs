<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    

<body>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">
                <img src="image/logo.jpg" width="50" height="50" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <h3>ระบบจัดการห้องสมุดออนไลน์</h3>

            <div class="collapse navbar-collapse mt-2" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_das.php">🏠 หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_book.php">📚 หนังสือ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_member.php">👥 สมาชิกห้องสมุด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_img.php">🖼️รูปภาพกิจกรมม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_depart.php">👥สมาชิกสาขา</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_bor.php">📋 ยืมคืน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_fine.php">💰 ค่าปรับ</a>
                    </li>
                    

                    
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_reports.php">📊 รายงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-danger" href="logout.php">🚪 ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

</body>
</html>
