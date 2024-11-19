<?php
// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
require_once 'connect_d.php';

// ตรวจสอบว่ามีการส่ง id_em มาหรือไม่
if (isset($_GET['id'])) {
    $id_em = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลพนักงานจาก id_em ที่ระบุ
    $query = "SELECT * FROM tbl_employee WHERE id_em = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_em);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบว่าพบข้อมูลพนักงานหรือไม่
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="css_crud/bootstrap.min.css" rel="stylesheet">
            <title>แก้ไขข้อมูลพนักงาน</title>
        </head>

        <body>
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> <br>
                        <h3>แก้ไขข้อมูลพนักงาน</h3>

                        <!-- แบบฟอร์มแก้ไขข้อมูลพนักงาน -->
                        <form action="formedit_db.php" method="POST">
                            <input type="hidden" name="id_em" value="<?= htmlspecialchars($employee['id_em']); ?>">

                            <div class="mb-3">
                                <label for="code" class="form-label">รหัสพนักงาน</label>
                                <input type="text" class="form-control" id="code" name="code" 
                                    value="<?= htmlspecialchars($employee['code']); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    value="<?= htmlspecialchars($employee['name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="surname" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="surname" name="surname" 
                                    value="<?= htmlspecialchars($employee['surname']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">เพศ</label>
                                <input type="text" class="form-control" id="gender" name="gender" 
                                    value="<?= htmlspecialchars($employee['gender']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="shift" class="form-label">ประจำกะ</label>
                                <input type="text" class="form-control" id="shift" name="shift" 
                                    value="<?= htmlspecialchars($employee['shift']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="machine" class="form-label">เครื่องจักร</label>
                                <input type="text" class="form-control" id="machine" name="machine" 
                                    value="<?= htmlspecialchars($employee['machine']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label">วันที่เริ่มงาน</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                    value="<?= htmlspecialchars($employee['start_date']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">วันที่สิ้นสุดงาน</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                    value="<?= htmlspecialchars($employee['end_date']); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">สิทธิ์การเข้าถึง</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="" disabled>กรุณาเลือกสิทธิ์การเข้าถึง</option>
                                    <option value="Admin" <?= $employee['role'] == 'Admin' ? 'selected' : ''; ?>>ผู้ดูแลระบบ</option>
                                    <option value="User" <?= $employee['role'] == 'User' ? 'selected' : ''; ?>>ผู้ใช้</option>
                                    <option value="Expired" <?= $employee['role'] == 'Expired' ? 'selected' : ''; ?>>ออกแล้ว</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
                        </form>

        <?php
    } else {
        echo "<p>ไม่พบข้อมูลพนักงาน</p>";
    }
} else {
    echo "<p>ไม่พบ ID ของพนักงาน</p>";
}
?>
                    </div>
                </div>
            </div>
        </body>

        </html>
