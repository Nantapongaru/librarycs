<?php include("header.php"); ?>
<?php include 'test-1.php';?>
<section class="main-content">
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">เงื่อนไขการยืมคืนหนังสือ</h1>
            <p class="lead">โปรดอ่านเงื่อนไขการยืมคืนหนังสือให้ละเอียดก่อนทำการยืม</p>
        </div>
        <div class="card shadow-lg">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>1.</strong> คนที่ยืมต้องเป็นอาจารย์และนักศึกษาสาขาวิทยาการคอมพิวเตอร์เท่านั้น</li>
                    <li class="list-group-item"><strong>2.</strong> อาจารย์สามารถยืมได้ 10 เล่ม</li>
                    <li class="list-group-item"><strong>3.</strong> นักศึกษาสามารถยืมได้ 5 เล่ม</li>
                    <li class="list-group-item"><strong>4.</strong> หนังสือมีอย่างละ 1 เล่ม เท่านั้น</li>
                    <li class="list-group-item"><strong>5.</strong> อาจารย์ยืมได้ไม่เกิน 60 วัน</li>
                    <li class="list-group-item"><strong>6.</strong> นักศึกษายืมได้ไม่เกิน 15 วัน</li>
                    <li class="list-group-item"><strong>7.</strong> ค่าปรับวันละ 20 บาท สูงสุด 500 บาท</li>
                </ul>
            </div>
        </div>
    </div>
    <?php include 'frequency_bor.php';?>
</section>

<?php include('footer.php'); ?>
