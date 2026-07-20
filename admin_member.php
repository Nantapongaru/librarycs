<?php
session_start();
include 'connect.php';
include 'header_admin.php';

if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายชื่อสมาชิก</title>
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

            <h3 class="mt-4">👥 รายชื่อสมาชิกทั้งหมด</h3>
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="add_member.php" class="btn btn-success">➕ เพิ่มสมาชิก</a>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">🔍 ค้นหา:</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="พิมพ์เพื่อค้นหา...">
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table id="memberTable" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>รหัสสมาชิก</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>เบอร์</th>
                            <th>ประเภทสมาชิก</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT id_m, user_m, name_m, tel_m, status_m FROM member") or die("เกิดข้อผิดพลาด SQL: " . mysqli_error($conn));

                        while ($row = mysqli_fetch_assoc($result)) {
                            $status = $row['status_m'] == 'นักศึกษา' ? "นักศึกษา" : "อาจารย์";
                            echo "<tr>
                                <td>{$row['id_m']}</td>
                                <td>{$row['user_m']}</td>
                                <td>{$row['name_m']}</td>
                                <td>{$row['tel_m']}</td>
                                <td>{$status}</td>
                                <td>
                                    <a href='edit_member.php?id_m={$row['id_m']}' class='btn btn-warning btn-sm'>✏️ แก้ไข</a>
                                    <a href='delete_member.php?id_m={$row['id_m']}' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบสมาชิกนี้?\");'>🗑️ ลบ</a>
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
            $('#memberTable').DataTable({
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

            // ฟังก์ชันค้นหาของคุณ
            $('#searchInput').on('keyup', function () {
                searchMember(); // เรียกฟังก์ชัน searchMember() เมื่อมีการพิมพ์ในช่องค้นหา
            });
        });

        // ฟังก์ชันค้นหา
        function searchMember() {
            let keyword = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#memberTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
            });
        }

    </script>

</body>

</html>
