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

            <h3 class="mt-4">📖 รายชื่อแผนกทั้งหมด</h3>
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="formadd_depart.php" class="btn btn-success">➕ เพิ่มสมาชิก</a>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">🔍 ค้นหา:</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="พิมพ์เพื่อค้นหา...">
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table id="departTable" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>รหัสข้อมูล</th>
                            <th>รหัสประจำตัว</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT id_data, id_depart, status_data FROM data_depart";
                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            echo "<tr><td colspan='4'>ไม่สามารถดึงข้อมูลแผนกได้</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                // ใช้เทคนิคการแปลงค่า status_data ให้แสดงเป็น 'นักศึกษา' หรือ 'อาจารย์'
                                $status = ($row['status_data'] == 'นักศึกษา') ? 'นักศึกษา' : 'อาจารย์';
                                echo "<tr>
                                    <td>{$row['id_data']}</td>
                                    <td>{$row['id_depart']}</td>
                                    <td>{$status}</td>
                                    <td>
                                        <a href='edit_depart.php?id_data={$row['id_data']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
                                        <a href='delete_depart.php?id_data={$row['id_data']}' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบแผนกนี้?\");'>🗑️ ลบ</a>
                                    </td>
                                </tr>";
                            }
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
            $('#departTable').DataTable({
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
                searchDepart(); // เรียกฟังก์ชัน searchDepart() เมื่อมีการพิมพ์ในช่องค้นหา
            });
        });

        // ฟังก์ชันค้นหา
        function searchDepart() {
            let keyword = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#departTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
            });
        }

    </script>

</body>

</html>
