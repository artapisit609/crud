<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "1023-a10", "employee");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    $query = $mysqli->prepare("SELECT password FROM tbl_employee WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] == '') {
            echo json_encode(['status' => 'set_password', 'message' => 'Password not set']);
        } else {
            echo json_encode(['status' => 'password_required', 'message' => 'Password required']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$mysqli->close();
?>
