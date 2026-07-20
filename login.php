<?php include("header.php"); ?>

<section class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">Login</h3>
                <form action="check.php" method="POST">
                    <div class="mb-3">
                        <label for="user_m" class="form-label">Username</label>
                        <input type="text" class="form-control" name="user_m" id="user_m" placeholder="ชื่อผู้ใช้"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="pass_m" class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass_m" id="pass_m" placeholder="รหัสผ่าน"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="user_type" class="form-label">ประเภทผู้ใช้งาน</label>
                        <select name="user_type" id="user_type" class="form-control" required>
                            <option value="member">สมาชิก</option>
                            <option value="admin">ผู้ดูแลระบบ</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content">
                        <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>