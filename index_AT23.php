<?php
require_once 'connect_d.php';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

$sql = "SELECT a.id_at23, a.code, m.name, a.date_atten, a.days_atten, a.type_atten
        FROM atten23 AS a
        INNER JOIN tbl_employee AS m ON a.code = m.code
        ORDER BY a.id_at23 ASC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css_crud/bootstrap.min.css" rel="stylesheet">
    <link href="css_crud/all.min.css" rel="stylesheet">
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

    <!-- <div class="header">
        <h3>ข้อมูลการลางาน 2023</h3>
    </div> -->

    <div class="content" id="content">

        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom"><i
                class="fas fa-arrow-down"></i></button>

        <table class="table table-striped table-hover table-responsive table-bordered">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">รหัสพนักงาน</th>
                    <th width="20%">ชื่อ</th>
                    <th width="20%">วันที่เริ่มลา</th>
                    <th width="15%">จำนวนวัน</th>
                    <th width="20%">ประเภทการลา</th>
                </tr>

            </thead>

            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $row['id_at23']; ?></td> <!-- Display the id -->
                            <td><?= $row['code']; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['date_atten']; ?></td>
                            <td><?= number_format($row['days_atten'], 2); ?></td>
                            <td><?= $row['type_atten']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>