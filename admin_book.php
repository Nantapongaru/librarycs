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

            <h3 class="mt-4">📖 รายการหนังสือทั้งหมด</h3>
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="add_book.php" class="btn btn-success">➕ เพิ่มหนังสือ</a>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">🔍 ค้นหา:</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="พิมพ์เพื่อค้นหา...">
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table id="bookTable" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>รหัสหนังสือ</th>
                            <th>ชื่อหนังสือ</th>
                            <th>ชื่อผู้เเต่ง</th>
                            <th>ประเภท</th>
                            <th>รูปภาพ</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT book.id_b, book.name_b, book.detail_b, type_book.type_name, book.pic_b, book.status_b 
                            FROM book 
                            INNER JOIN type_book ON book.type_b = type_book.type_id";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo "<tr>
                            <td>{$row['id_b']}</td>
                            <td>{$row['name_b']}</td>
                            <td>{$row['detail_b']}</td>
                            <td>{$row['type_name']}</td>
                            <td><img src='image/{$row['pic_b']}' width='80' height='80' class='rounded'></td>
                            <td>" . ($row['status_b'] == 1 ? "<span class='badge bg-success'>ยืมได้</span>" : "<span class='badge bg-danger'>ถูกยืมไปแล้ว</span>") . "</td>
                            <td>
                                <a href='edit_book.php?id_b={$row['id_b']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
                                <a href='delete_book.php?id_b={$row['id_b']}' class='btn btn-danger btn-sm mt-2' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบหนังสือนี้?\");'>🗑️ ลบ</a>
                            </td>
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
            $('#bookTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"]],
                "searching": false,  // 🔹 ปิดระบบค้นหาในตัวของ DataTables
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

            // ใช้ช่องค้นหาที่เพิ่มเองแทนของ DataTables
            $('#searchInput').on('keyup', function () {
                search_book();
            });
        });
        function search_book() {
            let keyword = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#bookTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
            });
        }

    </script>

</body>

</html>