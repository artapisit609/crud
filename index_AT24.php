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
    <!-- Sidebar menu -->
    <?php include "menu-02.php"; ?>
    <!-- Content area -->
    <div class="header">
        <h3>ข้อมูลการลางาน 2024</h3>
    </div>

    <div class="content" id="content">
        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom"><i
                class="fas fa-arrow-down"></i></button>
        <a href="formAT_ad" id="addBtn"><i class="fas fa-plus"></i></a>
        <div class="row-container">
            <form action="" method="GET" class="mb-6">
                <div class="row-container">
                    <div class="row-container">
                        <label for="datePicker" class="row-container">เลือกวันที่</label>
                    </div>
                    <div class="row-container">
                        <input type="date" id="datePicker" name="selected_date" class="form-control"
                            value="<?= isset($_GET['selected_date']) ? $_GET['selected_date'] : '' ?>">
                    </div>
                </div>
                <div class="row-container">
                    <div class="row-container">
                        <label for="employeeCode" class="row-container">รหัสพนักงาน</label>
                    </div>
                    <div class="row-container">
                        <input type="text" id="employeeCode" name="employee_code" class="form-control"
                            value="<?= isset($_GET['employee_code']) ? $_GET['employee_code'] : '' ?>">
                    </div>
                </div>
                <p> </p>
                <div class="row-container">
                    <div class="row-container">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </div>
            </form>
            <br>
        </div>
        <p> </p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">รหัสพนักงาน</th>
                    <th width="20%">ชื่อ</th>
                    <th width="20%">วันที่เริ่มลา</th>
                    <th width="15%">จำนวนวัน</th>
                    <th width="20%">ประเภทการลา</th>
                    <th width="10%">แก้ไข</th>
                    <th width="10%">ลบ</th>
                </tr>
            </thead>

            <?php
            require_once 'connect_d.php';
            if (isset($_GET['selected_date']) || isset($_GET['employee_code'])) {
                $selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
                $employee_code = isset($_GET['employee_code']) ? $_GET['employee_code'] : '';
                $sql = "SELECT a.id_at24, a.code, m.name, a.date_atten, a.days_atten, a.type_atten
            FROM atten24 a
            INNER JOIN tbl_employee m ON a.code = m.code
            WHERE 1=1";
                if (!empty($selected_date)) {
                    $sql .= " AND DATE(a.date_atten) = '$selected_date'";
                }

                if (!empty($employee_code)) {
                    $sql .= " AND a.code = '$employee_code'";
                }

                $result = $conn->query($sql);
            } else {
                $result = $conn->query("SELECT a.id_at24, a.code, m.name, a.date_atten, a.days_atten, a.type_atten
                            FROM atten24 a
                            INNER JOIN tbl_employee m ON a.code = m.code ORDER BY a.id_at24 ASC");
            }
            ?>

            <tbody>
                <?php while ($k = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $k['id_at24']; ?></td>
                        <td><?= $k['code']; ?></td>
                        <td><?= $k['name']; ?></td>
                        <td><?= $k['date_atten']; ?></td>
                        <td><?= number_format($k['days_atten'], 2); ?></td>
                        <td><?= $k['type_atten']; ?></td>
                        <td><a href="formAT_edit.php?id=<?= $k['id_at24']; ?><?= isset($_GET['selected_date']) ? '&selected_date=' . $_GET['selected_date'] : '' ?>&employee_code=<?= $k['code'] ?>"
                                class="btn btn-warning btn-sm">แก้ไข</a></td>
                        <td><a href="del_AT.php?id=<?= $k['id_at24']; ?><?= isset($_GET['selected_date']) ? '&selected_date=' . $_GET['selected_date'] : '' ?>&employee_code=<?= $k['code'] ?>"
                                class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>