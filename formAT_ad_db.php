<?php
// ถ้ามีค่าส่งมาจากฟอร์ม
if(isset($_POST['code']) && isset($_POST['date_atten']) && isset($_POST['type_atten']) && isset($_POST['days_atten'])){
    // ไฟล์เชื่อมต่อฐานข้อมูล
    require_once 'connect_d.php';

    // ประกาศตัวแปรรับค่าจากฟอร์ม
    $code = $_POST['code'];
    $date_atten = $_POST['date_atten'];
    $type_atten = $_POST['type_atten'];
    $days_atten = $_POST['days_atten'];

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli($servername, $username, $password, $database);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL insert statement
    $stmt = $conn->prepare("INSERT INTO atten24 (code, date_atten, type_atten, days_atten) VALUES (?, ?, ?, ?)");

    // กำหนดค่าตัวแปรแทน placeholder ใน statement
    $stmt->bind_param("isss", $code, $date_atten, $type_atten, $days_atten);

    // ปรับปรุงการแปลงรูปแบบวันที่
    $formattedStartDate = date("Y-m-d", strtotime($date_atten));

    // ประมวลผลคำสั่ง SQL
    $result = $stmt->execute();

    // ปิดการเชื่อมต่อ
    $conn->close();

    // Sweet Alert
    echo '<script src="js_crud/jquery-2.1.3.min.js"></script>';
    echo '<script src="js_crud/sweetalert-dev.js"></script>';
    echo '<link rel="stylesheet" href="css_crud/sweetalert.css">';

    // แก้ไขส่วนของ CSS
    echo '<style>
        /* รายละเอียด CSS ที่ต้องการแก้ไข */
    </style>';

    // แก้ไขส่วน JavaScript
    echo '<script>
        if(' . $result . '){
            setTimeout(function() {
                swal({
                    title: "เพิ่มข้อมูลสำเร็จ",
                    type: "success"
                }, function() {
                    window.location = "index_AT24.php"; // หน้าที่ต้องการให้กระโดดไป
                });
            }, 1000);
        } else {
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    type: "error"
                }, function() {
                    window.location = "index_AT24.php"; // หน้าที่ต้องการให้กระโดดไป
            });
        }, 1000);
    }
    </script>';
} // isset
?>
