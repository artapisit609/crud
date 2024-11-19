<?php 
if(isset($_GET['id'])){
    require_once 'connect_d.php';

    // ประกาศตัวแปรรับค่าจาก param method get
    $id = $_GET['id'];

    // สร้างคำสั่ง SQL สำหรับลบข้อมูล
    $sql = "DELETE FROM tbl_employee WHERE id_em = ?";
    
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ผูกค่า parameter
    $stmt->bind_param('i', $id);

    // execute คำสั่ง SQL
    $stmt->execute();

    // ตรวจสอบว่ามีการลบข้อมูลสำเร็จหรือไม่
    if($stmt->affected_rows == 1) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "ลบข้อมูลสำเร็จ",
                    type: "success"
                }, function() {
                    window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            }, 1000);
            </script>';
    } else {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    type: "error"
                }, function() {
                    window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            }, 1000);
            </script>';
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
