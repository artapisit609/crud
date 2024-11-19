<?php
include 'connect_d.php'; // เชื่อมต่อฐานข้อมูล

$username = $_POST['username'];
$password = $_POST['password'];
$face_image = $_FILES['face_image']['tmp_name'];

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// อ่านไฟล์ภาพเป็น binary
$face_image_data = file_get_contents($face_image);

$sql = "INSERT INTO users (username, password_hash, face_image) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $username, $password_hash, $face_image_data);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
