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
</style>

<body>
    <?php include "menu-02.php"; ?>
    <!-- Content area -->
    <div class="content" id="content">
        <!-- Header -->
        <div class="header">
            <h3>โปรไฟล์พนักงาน</h3>
        </div>
        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom"><i
                class="fas fa-arrow-down"></i></button>
        <div class="row">
            <div class="container">
                <div class="card">
                    <div class="card-header">ข้อมูลพนักงาน</div>
                    <div class="card-body">
                        <?php
                        require_once 'connect_d.php';

                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        if (isset($_GET['employee_code'])) {
                            $employee_code = $_GET['employee_code'];

                            $sql = "SELECT * FROM tbl_employee WHERE code = '$employee_code'";
                            $result = mysqli_query($conn, $sql);
                            $employee = mysqli_fetch_assoc($result);

                            if ($employee) {
                                function calculateJobDuration($startDate)
                                {
                                    $currentDate = date('Y-m-d');
                                    $diff = date_diff(date_create($startDate), date_create($currentDate));
                                    return $diff->format('%y ปี %m เดือน %d วัน');
                                }

                                echo "<table class='table'>";
                                echo "<tr><th>รหัสพนักงาน</th><td>" . $employee['code'] . "</td></tr>";
                                echo "<tr><th>ชื่อ</th><td>" . $employee['name'] . "</td></tr>";
                                echo "<tr><th>นามสกุล</th><td>" . $employee['surname'] . "</td></tr>";
                                echo "<tr><th>วันที่เริ่มงาน</th><td>" . $employee['start_date'] . "</td></tr>";
                                echo "<tr><th>อายุงาน</th><td>" . calculateJobDuration($employee['start_date']) . "</td></tr>";
                                echo "</table>";

                            } else {
                                echo "<p>ไม่พบข้อมูลพนักงาน</p>";
                            }
                        } else {
                            echo "<p>ไม่ได้ระบุรหัสพนักงาน</p>";
                        }
                        ?>
                    </div>
                </div>
                <br>

                <div class="card">
                    <div class="card-header">คะแนนประเมิน</div>
                    <div class="card-body">
                        <?php
                        $sql_point2301 = "SELECT * FROM point2301 WHERE code = '$employee_code'";
                        $result_point2301 = mysqli_query($conn, $sql_point2301);
                        $points2301 = mysqli_fetch_all($result_point2301, MYSQLI_ASSOC);

                        $sql_point2302 = "SELECT * FROM point2302 WHERE code = '$employee_code'";
                        $result_point2302 = mysqli_query($conn, $sql_point2302);
                        $points2302 = mysqli_fetch_all($result_point2302, MYSQLI_ASSOC);

                        $sql_point2401 = "SELECT * FROM point2401 WHERE code = '$employee_code'";
                        $result_point2401 = mysqli_query($conn, $sql_point2401);
                        $points2401 = mysqli_fetch_all($result_point2401, MYSQLI_ASSOC);

                        // ตรวจสอบว่ามีข้อมูลในทั้ง 3 ตาราง
                        if ($points2301 || $points2302 || $points2401) {
                            echo "<table class='table'>";
                            echo "<thead>
                    <tr>
                        <th>รายละเอียด</th>
                        <th>2301</th>
                        <th>2302</th>
                        <th>2401</th>
                    </tr>
                  </thead>";
                            echo "<tbody>";

                            // Assuming each table has the same columns: 'score', 'loss', 'grade'
                            $details = [
                                'คะแนนที่ได้' => ['total'],
                                'คะแนนสูญเสีย' => ['loss'],
                                'เกรดที่ได้' => ['grade']
                            ];

                            foreach ($details as $label => $fields) {
                                echo "<tr>";
                                echo "<td>$label</td>";
                                echo "<td>" . ($points2301[0][$fields[0]] ?? '-') . "</td>";
                                echo "<td>" . ($points2302[0][$fields[0]] ?? '-') . "</td>";
                                echo "<td>" . ($points2401[0][$fields[0]] ?? '-') . "</td>";
                                echo "</tr>";
                            }

                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "ไม่มีข้อมูลคะแนนประเมินสำหรับพนักงานนี้";
                        }
                        ?>
                    </div>
                </div>
                <br>

                <div class="card">
                    <div class="card-header">ข้อมูลการลา</div>
                    <div class="card-body">
                        <?php
                        $sql_attendance23 = "SELECT SUM(days_atten) AS total_days, type_atten FROM atten23 WHERE code = '$employee_code' GROUP BY type_atten";
                        $result_attendance23 = mysqli_query($conn, $sql_attendance23);
                        $attendances23 = mysqli_fetch_all($result_attendance23, MYSQLI_ASSOC);

                        $sql_attendance24 = "SELECT SUM(days_atten) AS total_days, type_atten FROM atten24 WHERE code = '$employee_code' GROUP BY type_atten";
                        $result_attendance24 = mysqli_query($conn, $sql_attendance24);
                        $attendances24 = mysqli_fetch_all($result_attendance24, MYSQLI_ASSOC);

                        if ($attendances23 || $attendances24) {
                            echo "<table class='table'>";
                            echo "<thead><tr><th>ประเภทการลา</th><th>จำนวนวัน (2023)</th><th>จำนวนวัน (2024)</th></tr></thead>";
                            echo "<tbody>";
                            $mergedAttendances = [];
                            foreach ($attendances23 as $attendance23) {
                                $mergedAttendances[$attendance23['type_atten']] = ['total_days_23' => $attendance23['total_days'], 'total_days_24' => 0];
                            }
                            foreach ($attendances24 as $attendance24) {
                                if (array_key_exists($attendance24['type_atten'], $mergedAttendances)) {
                                    $mergedAttendances[$attendance24['type_atten']]['total_days_24'] = $attendance24['total_days'];
                                } else {
                                    $mergedAttendances[$attendance24['type_atten']] = ['total_days_23' => 0, 'total_days_24' => $attendance24['total_days']];
                                }
                            }
                            foreach ($mergedAttendances as $type_atten => $attendance) {
                                echo "<tr><td>" . $type_atten . "</td><td>" . number_format($attendance['total_days_23'], 2) . "</td><td>" . number_format($attendance['total_days_24'], 2) . "</td></tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>ไม่พบข้อมูลการลา</p>";
                        }
                        ?>
                    </div>
                </div>
                <br>

                <?php
                $sql_point2401 = "SELECT behavior, performance, skill, attendance 
                    FROM point2401 
                    WHERE code = '$employee_code'";
                $result_point2401 = mysqli_query($conn, $sql_point2401);
                $result = mysqli_fetch_assoc($result_point2401);

                $max_score = 80;
                $max_behavior = $max_performance = $max_skill = $max_score / 4;
                $max_attendance = $max_score;

                $behavior_score = ($result['behavior'] / $max_behavior) * 20;
                $performance_score = ($result['performance'] / $max_performance) * 20;
                $skill_score = ($result['skill'] / $max_skill) * 20;
                //$attendance_score = ($result['attendance'] / $max_attendance) * 40;
                $attendance_score = max(0, ($result['attendance'] / $max_attendance) * 40);
                $scores = array(
                    'Behavior' => $behavior_score,
                    'Performance' => $performance_score,
                    'Skill' => $skill_score,
                    'Attendance' => $attendance_score
                );
                ?>
                <canvas id="barChart" width="100" height="75"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('barChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Behavior', 'Performance', 'Skill', 'Attendance'],
                datasets: [{
                    label: 'Scores',
                    data: [<?= isset($scores['Behavior']) ? $scores['Behavior'] : 0 ?>,
                        <?= isset($scores['Performance']) ? $scores['Performance'] : 0 ?>,
                        <?= isset($scores['Skill']) ? $scores['Skill'] : 0 ?>,
                        <?= isset($scores['Attendance']) ? $scores['Attendance'] : 0 ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 25,
                            max: 25,
                            min: 0
                        },
                        position: 'right', // เปลี่ยนตำแหน่งแกน y เป็น right
                        fixedStepSize: 25 // ให้ขนาดของข้อมูลเป็นคงที่ไม่ว่าจะมีข้อมูลเท่าไหร่ก็ตาม
                    }]
                }
            }
        });
    </script>

</body>

</html>