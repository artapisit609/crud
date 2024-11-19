<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "1023-a10", "employee");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $mysqli->prepare("UPDATE tbl_employee SET password = ? WHERE username = ?");
    $query->bind_param("ss", $password, $username);

    if ($query->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to set password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$mysqli->close();
?>
