<?php
session_start();
if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include "connect.php";
include 'header_admin.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card p-4">
            <h2 class="text-center">📚 Admin Dashboard</h2>

            <h3 class="mt-4">📖 รายการยืมหนังสือ</h3>
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="formadd_book.php" class="btn btn-success">➕ เพิ่มหนังสือ</a>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">🔍 ค้นหา:</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="พิมพ์เพื่อค้นหา...">
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table id="borrowTable" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>รหัสหนังสือ</th>
                            <th>ชื่อหนังสือ</th>
                            <th>ผู้ยืม</th>
                            <th>วันที่ยืม</th>
                            <th>กำหนดคืน</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT borrow.id_b, book.name_b, member.user_m, borrow.date_bor, borrow.date_re, borrow.status_b 
                                FROM borrow 
                                JOIN book ON borrow.id_b = book.id_b 
                                JOIN member ON borrow.id_m = member.id_m";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>
                                <td>{$row['id_b']}</td>
                                <td>{$row['name_b']}</td>
                                <td>{$row['user_m']}</td>
                                <td>{$row['date_bor']}</td>
                                <td>{$row['date_re']}</td>
                                <td>" . ($row['status_b'] == 1 ? "<span class='badge bg-success'>คืนแล้ว</span>" : "<span class='badge bg-danger'>ยังไม่คืน</span>") . "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#borrowTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"]],
                "searching": false,  // ปิดการใช้งานค้นหาของ DataTables
                "language": {
                    "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "infoEmpty": "ไม่มีข้อมูล",
                    "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    }
                }
            });

            // ใช้ฟังก์ชันค้นหาของคุณ
            $('#searchInput').on('keyup', function () {
                searchBorrow(); // เรียกฟังก์ชัน searchBorrow() เมื่อมีการพิมพ์ในช่องค้นหา
            });
        });

        // ฟังก์ชันค้นหา
        function searchBorrow() {
            let keyword = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#borrowTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
            });
        }

    </script>

</body>

</html>
