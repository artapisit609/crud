<?php
include "auth.php";
require_once "connect_d.php";
// สร้างการเชื่อมต่อ MySQLi
$conn = new mysqli($servername, $username, $password, $database);
// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันนับจำนวนพนักงาน
function countEmployees($conn, $condition)
{
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_employee WHERE (end_date IS NULL OR end_date  = 0) AND $condition");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

// นับจำนวนพนักงานทั้งหมด
$totalEmployees = countEmployees($conn, "1=1");
// นับจำนวนพนักงานชาย
$maleEmployees = countEmployees($conn, "gender = 'ชาย'");
// นับจำนวนพนักงานหญิง
$femaleEmployees = countEmployees($conn, "gender = 'หญิง'");
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
// คำนวณอัตรากำลังการผลิต
$productionRate = (($totalEmployees - 4) / (20 * 2)) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css_crud/bootstrap.min.css" rel="stylesheet">
    <link href="css_crud/all.min.css" rel="stylesheet">
    <script src="js_crud/up-down.js"></script>
    <title>Horizontal HR Systems</title>
    <style>
        .card {
            top: 50px;
            margin: 20px 0;
            padding: 10px;
            background-color: hsl(187, 50%, 75%);
            border-radius: 50px;
            border-color: blue;
            box-shadow: 0 5px 5px rgba(120, 200, 200, 0.5);
            font-size: 24px;
            color: darkorchid;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include "menu-02.php"; ?>
    <!-- Content area -->
    <div class="content" id="content">
        <!-- Header -->
        <div class="header">
            <h3>Horizontal HR Systems</h3>
        </div>
            <div class="card">
                <h4>อัตรากำลังการผลิตปัจจุบัน : <?php echo number_format($productionRate, 2); ?> %</h4>
            </div>
            <div class="card">
                <h5>จำนวนเครื่องจักร : 20 เครื่อง</h5>
            </div>
            <div class="card">
                <h5>จำนวนพนักงานทั้งหมด : <?php echo $totalEmployees - 4; ?> คน</h5>
            </div>
            <div class="card">
                <h5>พนักงานชาย : <?php echo $maleEmployees - 4; ?> คน</h5>
            </div>
            <div class="card">
                <h5>พนักงานหญิง : <?php echo $femaleEmployees; ?> คน</h5>
            </div>
            <div class="card">
                <h5>จำนวนพนักงานที่ขาด : <?php echo max(0, (20 * 2) - ($totalEmployees - 4)); ?> คน</h5>
            </div>
    </div>
</body>

</html>