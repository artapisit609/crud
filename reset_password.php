<?php
session_start();
include('connect_d.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    if (empty($username) || empty($newPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    // เข้ารหัสรหัสผ่านใหม่
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // อัปเดตรหัสผ่านในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE tbl_employee SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashedPassword, $username);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถตั้งรหัสผ่านใหม่ได้']);
    }
}
?>
