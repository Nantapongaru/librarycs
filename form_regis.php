<?php include("header.php"); ?>

<section class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">ลงทะเบียนสมาชิก</h3>
                <form action="insert-regis.php" method="post">
                    <div class="mb-3">
                        <label for="id_m" class="form-label">รหัสประจำตัว</label>
                        <input type="text" class="form-control" name="id_m" id="id_m" required placeholder="รหัสนักศึกษา/รหัสอาจารย์">
                    </div>
                    <div class="mb-3">
                        <label for="name_m" class="form-label">ชื่อ-สกุล</label>
                        <input type="text" class="form-control" name="name_m" id="name_m" placeholder="ชื่อ-สกุล">
                    </div>
                    <div class="mb-3">
                        <label for="tel_m" class="form-label">เบอร์โทร</label>
                        <input type="text" class="form-control" name="tel_m" id="tel_m" maxlength="10" placeholder="xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label for="user_m" class="form-label">Username</label>
                        <input type="text" class="form-control" name="user_m" id="user_m" placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <label for="pass_m" class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass_m" id="pass_m" placeholder="Password">
                    </div>
                    <div class="d-flex justify-content">
                        <button type="submit" class="btn btn-primary">ลงทะเบียน</button>
                        <button type="reset" class="btn btn-secondary ms-2">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>
