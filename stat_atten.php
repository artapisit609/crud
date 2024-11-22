<?php
require_once "connect_d.php";

// สร้างการเชื่อมต่อ MySQLi
$conn = new mysqli($servername, $username, $password, $database);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันสำหรับดึงข้อมูลการลาที่ได้รับความนิยมสูงสุด
function fetchTopTypes($conn, $tableName)
{
    $query = "SELECT type_atten, COUNT(*) AS count FROM " . $conn->real_escape_string($tableName) . " GROUP BY type_atten ORDER BY count DESC LIMIT 3";
    $result = $conn->query($query);
    $topTypes = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topTypes[$row['type_atten']] = $row['count'];
        }
    }
    return $topTypes;
}

// ฟังก์ชันสำหรับดึงข้อมูลการลาของแต่ละเดือน
function fetchAttendanceData($conn, $tableName, $year)
{
    $query = "SELECT 
                MONTH(date_atten) AS month, 
                MONTHNAME(date_atten) AS month_name, 
                ROUND(SUM(days_atten), 2) AS total_days_atten 
              FROM " . $conn->real_escape_string($tableName) . " 
              WHERE YEAR(date_atten) = ? 
              GROUP BY MONTH(date_atten), MONTHNAME(date_atten)
              ORDER BY MONTH(date_atten)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendanceData = array_fill(1, 12, 0);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $month = $row['month'];
            $attendanceData[$month] = $row['total_days_atten'];
        }
    }
    $stmt->close();
    return $attendanceData;
}

// นับจำนวนพนักงาน
function countEmployees($conn, $condition = "")
{
    $sql = "SELECT COUNT(*) AS count FROM tbl_employee WHERE (end_date IS NULL OR end_date > CURDATE()) " . $condition;
    $result = $conn->query($sql);
    if ($result === false) {
        // Handle query error
        error_log("SQL Error in countEmployees: " . $conn->error);
        return 0;
    }
    $row = $result->fetch_assoc();
    return $row['count'];
}

$totalEmployees = countEmployees($conn);
$maleEmployees = countEmployees($conn, "AND gender = 'ชาย'");
$femaleEmployees = countEmployees($conn, "AND gender = 'หญิง'");

// ดึงข้อมูลการลาสูงสุดสำหรับปี 2023 และ 2024
$topTypes2023 = fetchTopTypes($conn, "atten23");
$topTypes2024 = fetchTopTypes($conn, "atten24");

// ดึงข้อมูลการลาของแต่ละเดือนสำหรับปี 2023 และ 2024
$attendanceData2023 = fetchAttendanceData($conn, "atten23", 2023);
$attendanceData2024 = fetchAttendanceData($conn, "atten24", 2024);

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css_crud/bootstrap.min.css" rel="stylesheet">
    <link href="css_crud/all.min.css" rel="stylesheet">
    <script src="js_crud/loader.js"></script>
    <script src="js_crud/chart.js"></script>
    <script src="js_crud/hide_sidebar02.js"></script>
    <script src="js_crud/up-down.js"></script>
    <title>Horizontal HR Systems</title>
</head>

<style>
    .content {
        margin-top: 20px;
        z-index: 998;
    }

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

<body>
    <?php include "menu-02.php"; ?>
    
    <canvas id="attendanceChart"></canvas>
    <!-- Content area -->
    <div class="content" id="content">
        <!-- Header -->
        <!-- <div class="header">
            <h3>เปรียบเทียบสถิติการลางาน 2023 - 2024</h3>
        </div> -->
        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom"><i
                class="fas fa-arrow-down"></i></button>

        <div class="row-container">
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var attendanceData2023 = <?php echo json_encode($attendanceData2023); ?>;
                    var attendanceData2024 = <?php echo json_encode($attendanceData2024); ?>;

                    var ctx = document.getElementById('attendanceChart').getContext('2d');
                    var attendanceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '2023',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                data: Object.values(attendanceData2023)
                            }, {
                                label: '2024',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                data: Object.values(attendanceData2024)
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>
        </div>

        <div class="row-container">

            <div class="card">
                <center>
                    <h3 class="card-title">สถิติการลางาน2023</h3>
                    <?php foreach ($topTypes2023 as $type => $count): ?>
                        <p class="card-data">
                            <?php echo htmlspecialchars($type) . ": " . htmlspecialchars($count) . " วัน"; ?>
                        </p>
                    <?php endforeach; ?>
                </center>
            </div><br>

            <div class="card">
                <center>
                    <h3 class="card-title">สถิติการลางาน2024</h3>
                    <?php foreach ($topTypes2024 as $type => $count): ?>
                        <p class="card-data">
                            <?php echo htmlspecialchars($type) . ": " . htmlspecialchars($count) . " วัน"; ?>
                        </p>
                    <?php endforeach; ?>
                </center>
            </div>


        </div>
    </div>
</body>

</html>