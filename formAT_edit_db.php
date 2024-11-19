<?php
// Check if form data is submitted
if(isset($_POST['code']) && isset($_POST['date_atten']) && isset($_POST['days_atten']) && isset($_POST['type_atten']) && isset($_POST['id'])) {
    // Include database connection file
    require_once 'connect_d.php';

    // Get form data
    $code = $_POST['code'];
    $date_atten = $_POST['date_atten'];
    $days_atten = $_POST['days_atten'];
    $type_atten = $_POST['type_atten'];

    // Adjust date format
    $formattedStartDate = date("Y-m-d", strtotime($date_atten));

    // Escape variables for security (optional but recommended)
    $code = mysqli_real_escape_string($conn, $code);
    $formattedStartDate = mysqli_real_escape_string($conn, $formattedStartDate);
    $days_atten = mysqli_real_escape_string($conn, $days_atten);
    $type_atten = mysqli_real_escape_string($conn, $type_atten);

    // SQL UPDATE
    $query = "UPDATE atten24 SET code = '$code', date_atten = '$formattedStartDate', type_atten = '$type_atten', days_atten = '$days_atten' WHERE id_at24 = '{$_POST['id']}'";

    $result = mysqli_query($conn, $query);

    // Include SweetAlert library
    echo '
    <script src="js_crud/jquery-2.1.3.min.js"></script>
    <script src="js_crud/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="css_crud/sweetalert.css">';

    if($result){
        echo '<script>
             setTimeout(function() {
              swal({
                  title: "แก้ไขข้อมูลสำเร็จ",
                  type: "success"
              }, function() {
                  window.location = "index_AT24.php"; // Redirect to desired page
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
                  window.location = "index_AT24.php"; // Redirect to desired page
              });
            }, 1000);
        </script>';
    }

    // Close database connection
    mysqli_close($conn);
} // isset
?>
