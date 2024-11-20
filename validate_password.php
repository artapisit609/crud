<?php
session_start();
include "connect_d.php";
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordNotSet = $_POST['passwordNotSet'] ?? '0';

    if (empty($username)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกรหัสพนักงาน']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM tbl_employee WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($passwordNotSet === '1') {
            echo json_encode(['status' => 'password_reset_required']);
            exit;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้งาน']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'คำขอไม่ถูกต้อง']);
}
?>