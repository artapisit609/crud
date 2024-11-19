<?php
// Check if form data is submitted
if (
    isset($_POST['code'], $_POST['name'], $_POST['surname'], $_POST['gender'], $_POST['shift'], $_POST['machine'], $_POST['start_date'], $_POST['role'])
) {
    // Include database connection file
    require_once 'connect_d.php';

    // Validate and sanitize input data
    $code = intval($_POST['code']);
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $shift = htmlspecialchars(trim($_POST['shift']));
    $machine = htmlspecialchars(trim($_POST['machine']));
    $start_date = htmlspecialchars(trim($_POST['start_date']));
    $role = htmlspecialchars(trim($_POST['role']));

    // Check database connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL statement without including username and password columns
    $stmt = $conn->prepare("
        INSERT INTO tbl_employee (code, name, surname, gender, shift, machine, start_date, role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param('isssssss', $code, $name, $surname, $gender, $shift, $machine, $start_date, $role);

        // Execute statement
        if ($stmt->execute()) {
            // Include SweetAlert library
            echo '
            <script src="js_crud/jquery-2.1.3.min.js"></script>
            <script src="js_crud/sweetalert-dev.js"></script>
            <link rel="stylesheet" href="css_crud/sweetalert.css">
            <script>
                setTimeout(function() {
                    swal({
                        title: "เพิ่มข้อมูลสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "index.php"; // Redirect to desired page
                    });
                }, 1000);
            </script>';
        } else {
            echo '
            <script src="js_crud/jquery-2.1.3.min.js"></script>
            <script src="js_crud/sweetalert-dev.js"></script>
            <link rel="stylesheet" href="css_crud/sweetalert.css">
            <script>
                setTimeout(function() {
                    swal({
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถเพิ่มข้อมูลได้",
                        type: "error"
                    }, function() {
                        window.location = "index.php"; // Redirect to desired page
                    });
                }, 1000);
            </script>';
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid form submission.";
}
?>
