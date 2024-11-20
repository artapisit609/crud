<?php
session_start();

// ตรวจสอบว่าเป็น HTTPS หรือ HTTP และสร้าง URL ให้ถูกต้อง
$uri = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
$uri .= $_SERVER['HTTP_HOST'];

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // หากไม่ได้ล็อกอิน นำไปที่หน้า login.php
    header('Location: '.$uri.'/Login/');
    exit;
}
?>