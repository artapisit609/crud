<?php
header('Content-Type: application/json');
require_once 'connect_d.php';

if (!$conn) {
    echo json_encode(['error' => 'Failed to connect to the database.']);
    exit;
}

if (isset($_GET['employee_code'])) {
    $employee_code = $_GET['employee_code'];

    // ดึงข้อมูลพนักงาน
    $sql = "SELECT * FROM tbl_employee WHERE code = '$employee_code'";
    $result = mysqli_query($conn, $sql);
    $employee = mysqli_fetch_assoc($result);

    if ($employee) {
        // ดึงข้อมูลคะแนน 2301, 2302, 2401
        $points = [];
        $tables = ['point2301', 'point2302', 'point2401'];
        foreach ($tables as $table) {
            $sql_point = "SELECT * FROM $table WHERE code = '$employee_code'";
            $result_point = mysqli_query($conn, $sql_point);
            $points[$table] = mysqli_fetch_all($result_point, MYSQLI_ASSOC);
        }

        // ดึงข้อมูลการลา 2023, 2024
        $attendances = [];
        $years = ['23' => 'atten23', '24' => 'atten24'];
        foreach ($years as $key => $table) {
			$sql_attendance = "SELECT SUM(days_atten) AS total_days, type_atten FROM $table WHERE code = '$employee_code' GROUP BY type_atten";
			$result_attendance = mysqli_query($conn, $sql_attendance);
			$attendances[$table] = mysqli_fetch_all($result_attendance, MYSQLI_ASSOC);
		}

        // คำนวณอายุงาน
        function calculateJobDuration($startDate)
        {
            $currentDate = date('Y-m-d');
            $diff = date_diff(date_create($startDate), date_create($currentDate));
            return $diff->format('%y ปี %m เดือน %d วัน');
        }

        // รวบรวมข้อมูลทั้งหมด
        $response = [
            'employee' => $employee,
            'job_duration' => calculateJobDuration($employee['start_date']),
            'points' => $points,
            'attendances' => $attendances
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Employee not found.']);
    }
} else {
    echo json_encode(['error' => 'Employee code not provided.']);
}
?>
