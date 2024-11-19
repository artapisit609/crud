<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

function countEmployees($conn, $condition)
{
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tbl_employee WHERE end_date = '0000-00-00' AND $condition");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

$total_employees = countEmployees($conn, "1=1");

$male_employees = countEmployees($conn, "gender = 'ชาย' AND machine <> 'หัวหน้างาน'");

$female_employees = countEmployees($conn, "gender = 'หญิง' AND machine <> 'หัวหน้างาน'");

$total_leader = countEmployees($conn, "machine = 'หัวหน้างาน'");

$total_machine = 20;

$shift = 2;

$operatefull = $total_machine * $shift;

$total_operate = $total_employees - $total_leader;

// Prepare the data as JSON
$data = array(
    'machine' => $total_machine,
    'total' => $total_employees,
    'male' => $male_employees - 1,
    'female' => $female_employees,
    'leader' => $total_leader + 1,
    'rate' => $total_operate / $operatefull
);

// Set the content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($data);

// Close the connection
$conn->close();