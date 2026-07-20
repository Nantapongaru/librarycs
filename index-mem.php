<?php session_start(); ?>
<?php include("header1.php");
 
if (!isset($_SESSION['ses_username'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการยืมหนังสือ'); window.location='login.php';</script>";
    exit();
}
include 'test-1.php';

?>

<section class="main-content">
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>ยินดีต้อนรับ คุณ <?php echo $_SESSION['ses_username']; ?> (รหัส <?php echo $_SESSION['id_m']; ?>)</h1>
            <h2 class="mt-4">เงื่อนไขการยืมคืนหนังสือ</h2>
        </div>

        <div class="card p-4 shadow-lg">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">1. คนที่ยืมต้องเป็นอาจารย์และนักศึกษาสาขาวิทยาการคอมพิวเตอร์เท่านั้น</li>
                <li class="list-group-item">2. อาจารย์สามารถยืมได้ 10 เล่ม</li>
                <li class="list-group-item">3. นักศึกษาสามารถยืมได้ 5 เล่ม</li>
                <li class="list-group-item">4. หนังสือมีเพียง 1 เล่มเท่านั้น</li>
                <li class="list-group-item">5. อาจารย์ยืมได้ไม่เกิน 60 วัน</li>
                <li class="list-group-item">6. นักศึกษายืมได้ไม่เกิน 15 วัน</li>
                <li class="list-group-item">7. ค่าปรับวันละ 20 บาท สูงสุด 500 บาท</li>
            </ul>
        </div>
    </div>
</section>
<?php include 'frequency_bor.php'; ?>
<?php include('footer.php'); ?>
