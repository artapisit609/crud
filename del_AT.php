<?php 
if(isset($_GET['id'])){
    require_once 'connect_d.php';
    //ประกาศตัวแปรรับค่าจาก param method get
    $id = $_GET['id'];
    
    // SQL statement
    $sql = "DELETE FROM atten24 WHERE id_at24 = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("i", $id);
    
    // Execute statement
    $stmt->execute();

    //  sweet alert 
    echo '
    <script src="js_crud/jquery-2.1.3.min.js"></script>
    <script src="js_crud/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="css_crud/sweetalert.css">';

    if($stmt->affected_rows == 1){
        echo '<script>
             setTimeout(function() {
              swal({
                  title: "ลบข้อมูลสำเร็จ",
                  type: "success"
              }, function() {
                  window.location = "index_AT24.php"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1000);
        </script>';
    }else{
       echo '<script>
             setTimeout(function() {
              swal({
                  title: "เกิดข้อผิดพลาด",
                  type: "error"
              }, function() {
                  window.location = "index_AT24.php"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1000);
        </script>';
    }
    $conn->close();
} //isset
?>
