<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css_crud/bootstrap.min.css" rel="stylesheet">
  <title>Horizontal HR Systems</title>
</head>

<body>
  <div class="cardcontainer">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <br>
        <h4>ฟอร์มเพิ่มข้อมูล</h4>
        <form action="formAdd_db.php" method="post">
          <div class="form-group">
            <label for="code" class="col-form-label">รหัสพนักงาน:</label>
            <input type="number" name="code" class="form-control" required minlength="3" placeholder="รหัสพนักงาน">
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">ชื่อ:</label>
            <input type="text" name="name" class="form-control" required minlength="3" placeholder="ชื่อ">
          </div>
          <div class="form-group">
            <label for="surname" class="col-form-label">นามสกุล:</label>
            <input type="text" name="surname" class="form-control" required minlength="3" placeholder="นามสกุล">
          </div>
          <div class="form-group">
            <label for="gender" class="col-form-label">เพศ:</label>
            <select id="gender" name="gender" class="form-control" required>
              <option value="" disabled selected>กรุณาเลือกเพศ</option>
              <option value="ชาย">ชาย</option>
              <option value="หญิง">หญิง</option>
            </select>
          </div>
          <div class="form-group">
            <label for="shift" class="col-form-label">ประจำกะ:</label>
            <select id="shift" name="shift" class="form-control" required>
              <option value="" disabled selected>กรุณาเลือกกะ</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="D">D</option>
            </select>
          </div>
          <div class="form-group">
            <label for="machine" class="col-form-label">เครื่องจักร:</label>
            <input type="text" name="machine" class="form-control" required minlength="5" placeholder="เครื่องจักร">
          </div>
          <div class="form-group">
            <label for="start_date" class="col-form-label">วันที่เริ่มงาน:</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="role" class="col-form-label">สิทธิ์การเข้าถึง:</label>
            <select id="role" name="role" class="form-control" required>
              <option value="" disabled selected>กรุณาเลือกสิทธิ์การเข้าถึง</option>
              <option value="Admin">ผู้ดูแลระบบ</option>
              <option value="User">ผู้ใช้</option>
              <option value="Expired">ออกแล้ว</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
