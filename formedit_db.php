<?php
// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
require_once 'connect_d.php';

// ตรวจสอบว่ามีการส่งค่าผ่าน POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีค่า id_em และข้อมูลที่จำเป็นส่งมาหรือไม่
    if (
        isset($_POST['id_em'], $_POST['name'], $_POST['surname'], $_POST['gender'], 
        $_POST['shift'], $_POST['machine'], $_POST['start_date'], $_POST['role'])
    ) {
        // กำหนดค่าตัวแปรจากข้อมูลที่ส่งมา และกรองข้อมูล
        $id_em = intval($_POST['id_em']);
        $name = htmlspecialchars(trim($_POST['name']));
        $surname = htmlspecialchars(trim($_POST['surname']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $shift = htmlspecialchars(trim($_POST['shift']));
        $machine = htmlspecialchars(trim($_POST['machine']));
        $start_date = htmlspecialchars(trim($_POST['start_date']));
        $role = htmlspecialchars(trim($_POST['role']));

        // เตรียมคำสั่ง SQL เพื่ออัปเดตข้อมูลของพนักงานในฐานข้อมูล
        $sql = "UPDATE tbl_employee 
                SET name = ?, surname = ?, gender = ?, shift = ?, machine = ?, start_date = ?, role = ?
                WHERE id_em = ?";
        
        // ใช้ Prepared Statements
        if ($stmt = $conn->prepare($sql)) {
            // ผูกค่าพารามิเตอร์
            $stmt->bind_param("sssssssi", $name, $surname, $gender, $shift, $machine, $start_date, $role, $id_em);

            // ดำเนินการคำสั่ง SQL
            if ($stmt->execute()) {
                echo "<script>alert('อัปเดตข้อมูลเรียบร้อยแล้ว'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error . "');</script>";
            }

            // ปิด statement
            $stmt->close();
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL');</script>";
        }
    } else {
        echo "<script>alert('ข้อมูลที่ส่งมาไม่ครบถ้วน');</script>";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
