<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css_crud/bootstrap.min.css" rel="stylesheet">
    <!--<link href="css_crud/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">-->
    <title>Form add attendant data</title>
  </head>
	
  <body>
    <div class="cardcontainer">
      <div class="row justify-content-center">
        <div class="col-md-6"> <br> 
          <h4>ฟอร์มเพิ่มข้อมูลการลา</h4>
          
          <form action="formAT_ad_db.php" method="post">
                <label for="code" class="col-sm-4 col-form-label"> รหัสพนักงาน : </label>
                <input type="intval" name="code" class="form-control" required minlength="4" placeholder="รหัสพนักงาน">
                <label for="date_atten" class="col-sm-4 col-form-label"> วันที่เริ่มลา : </label>
                <input type="date" name="date_atten" class="form-control" required placeholder="วันที่เริ่มลา">
                <label for="type_atten" class="col-sm-4 col-form-label"> ประเภทการลา : </label>
                    <select id="type_atten" name="type_atten" class="form-control" required>
                        <option value=""></option>
                        <option value="พักร้อน">พักร้อน</option>
                        <option value="พักร้อนฉุกเฉิน">พักร้อนฉุกเฉิน</option>
                        <option value="กิจ">กิจ</option>
                        <option value="กิจในสิทธิ์">กิจในสิทธิ์</option>
                        <option value="ป่วย">ป่วย</option>
                        <option value="ป่วยโควิด">ป่วยโควิด</option>
                    </select>
                <label for="days_atten" class="col-sm-4 col-form-label"> จำนวนวัน : </label>
                <input type="text" name="days_atten" class="form-control" required minlength="1" placeholder="จำนวนวัน">
                <br>
            <button class="btn btn-primary">เพิ่มข้อมูล</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
