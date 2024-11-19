<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array("message" => "Connection failed"));
    exit();
}

$action = $_POST['action'] ?? '';

if ($action == 'signup') {
    $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
    $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);

    if (!$user || !$pass || !$code || !$tel) {
        echo json_encode(array("message" => "Invalid input"));
        exit();
    }

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO hr_user (username, password, code, tel) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $hashed_password, $code, $tel);

    if ($stmt->execute()) {
        echo json_encode(array("message" => "Signup successful"));
    } else {
        echo json_encode(array("message" => "Error occurred"));
    }
    $stmt->close();
}

if ($action == 'login') {
    $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$user || !$pass) {
        echo json_encode(array("message" => "Invalid input"));
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM hr_user WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            // Generate a secure session token
            $token = bin2hex(random_bytes(32));
            // Store the token in the database or a secure session store
            // ...

            echo json_encode(array("message" => "Login successful", "token" => $token));
        } else {
            echo json_encode(array("message" => "Invalid credentials"));
        }
    } else {
        echo json_encode(array("message" => "Invalid credentials"));
    }
    $stmt->close();
}

$conn->close();
?>