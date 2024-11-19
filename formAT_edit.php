<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css_crud/bootstrap.min.css" rel="stylesheet">
    <title>Edit attendance data</title>
</head>

<body>
    <?php
if(isset($_GET['id'])){
    require_once 'connect_d.php';
    
    // กำหนดค่าตัวแปร id จากพารามิเตอร์ GET
    $id = $_GET['id'];

    // ทำการคิวรี่ข้อมูลโดยใช้ MySQLi
    $query = "SELECT * FROM atten24 WHERE id_at24=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // ถ้าไม่มีข้อมูลในฐานข้อมูล ให้ redirect กลับไปที่หน้า index_AT24s.php
    if($result->num_rows < 1){
        header('Location: index_AT24s.php');
        exit();
    }

    // ดึงข้อมูลแถวเดียว
    $row = $result->fetch_assoc();
}
?>

    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <br>
            <h4>ฟอร์มแก้ไขข้อมูล</h4>
            <form action="formAT_edit_db.php" method="post">
                <div class="mb-3">
                    <label for="code" class="form-label">รหัสพนักงาน:</label>
                    <input type="number" name="code" class="form-control" required minlength="4" placeholder="รหัสพนักงาน" value="<?= $row['code']; ?>">
                    </div>

                <div class="mb-3">
                    <label for="date_atten" class="form-label"> วันที่เริ่มลา :  </label>
                    <input type="date" name="date_atten" class="form-control" required minlength="3" placeholder="วันที่เริ่มลา" value="<?= $row['date_atten']; ?>">
                    </div>

                <div class="mb-3">
                    <label for="type_atten" class="form-label"> ประเภทการลา :  </label>
                        <select name="type_atten" class="form-control" required>
                            <option value=""></option>
                            <option value="พักร้อน" <?= ($row['type_atten'] == 'พักร้อน') ? 'selected' : ''; ?>>พักร้อน</option>
                            <option value="พักร้อนฉุกเฉิน" <?= ($row['type_atten'] == 'พักร้อนฉุกเฉิน') ? 'selected' : ''; ?>>พักร้อนฉุกเฉิน</option>
                            <option value="กิจ" <?= ($row['type_atten'] == 'กิจ') ? 'selected' : ''; ?>>กิจ</option>
                            <option value="กิจในสิทธิ์" <?= ($row['type_atten'] == 'กิจในสิทธิ์') ? 'selected' : ''; ?>>กิจในสิทธิ์</option>
                            <option value="ป่วย" <?= ($row['type_atten'] == 'ป่วย') ? 'selected' : ''; ?>>ป่วย</option>
                            <option value="ป่วยโควิด" <?= ($row['type_atten'] == 'ป่วยโควิด') ? 'selected' : ''; ?>>ป่วยโควิด</option>
                        </select>
                    </div>

                <div class="mb-3">
                    <label for="days_atten" class="form-label"> จำนวนวัน :  </label>
                    <input type="text" name="days_atten" class="form-control" required minlength="1" placeholder="จำนวนวัน" value="<?= $row['days_atten']; ?>">
                    </div>

                <input type="hidden" name="id" value="<?= $row['id_at24']; ?>">
                <button type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
