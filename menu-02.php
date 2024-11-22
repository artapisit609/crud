<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css_crud/menu-02.css" rel="stylesheet">
    <link href="css_crud/menu-navbar.css" rel="stylesheet">
    <link href="fontawesome-free-6.5.2-web/css/all.min.css" rel="stylesheet">
    <script src="js_crud/up-down.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="http://10.10.22.88/nav.html" class="menu-item">TMY</a>
            <a href="startpage"><i class="fas fa-home"></i>Home page</a>
            <a href="index"><i class="fas fa-bars"></i>รายชื่อพนักงาน</a>
            <a href="index_AT23"><i class="fas fa-history"></i>ประวัติการลางาน 2023</a>
            <a href="index_AT24"><i class="fas fa-history"></i>ประวัติการลางาน 2024</a>
            <a href="stat_atten"><i class="fas fa-list"></i>สถิติการลางาน</a>
            <a href="http://10.10.22.88/Login/logout.php" class="menu-item" data-tooltip="Logout"><i class="fas fa-sign-out-alt"></i>ออกจากระบบ</a>
        </div>
    </nav>

    <div id="content" class="content">
        <button onclick="scrollToTop()" id="scrollTopBtn" title="Go to top">
            <i class="fas fa-arrow-up"></i>
        </button>
        <button onclick="scrollToBottom()" id="scrollBottomBtn" title="Go to bottom">
            <i class="fas fa-arrow-down"></i>
        </button>
    </div>

</body>

</html>