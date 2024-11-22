<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css_crud/bootstrap.min.css" rel="stylesheet">
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
        <h3>รายชื่อพนักงาน</h3>
    </div> -->

    <div class="content" id="content">
        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom"><i
                class="fas fa-arrow-down"></i></button>
        <a href="formAdd" id="addBtn"><i class="fas fa-plus"></i></a>
        <br>

        <table class="table table-striped table-hover table-responsive table-bordered">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="25%">รหัสพนักงาน</th>
                    <th width="30%">ชื่อ</th>
                    <th width="30%">นามสกุล</th>
                    <th width="5%">แก้ไข</th>
                    <th width="5%">ลบ</th>
                </tr>
            </thead>

            <?php
            require_once 'connect_d.php';
            function calculateJobDuration($startDate)
            {
                $currentDate = date('Y-m-d');
                $diff = date_diff(date_create($startDate), date_create($currentDate));
                return $diff->format('%y ปี %m เดือน %d วัน');
            }

            $sql = "SELECT *, ROW_NUMBER() OVER (ORDER BY code) AS row_num FROM tbl_employee WHERE (end_date IS NULL OR end_date  = 0)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tbody>
                        <tr>
                            <td><?= $row['id_em']; ?></td>
                            <td><?= $row['code']; ?></td>
                            <td><a href="profile?employee_code=<?= $row['code']; ?>"
                                    class="btn btn-link"><?= $row['name']; ?></a></td>
                            <td><?= $row['surname']; ?></td>
                            <td><a href="formedit?id=<?= $row['id_em']; ?><?= isset($_GET['selected_date']) ? '&selected_date=' . $_GET['selected_date'] : '' ?>"
                                    class="btn btn-warning btn-sm">แก้ไข</a></td>
                            <td><a href="del?id=<?= $row['id_em']; ?><?= isset($_GET['selected_date']) ? '&selected_date=' . $_GET['selected_date'] : '' ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                        </tr>
                        <?php
                }
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>

            </tbody>
        </table>
    </div>
</body>

</html>