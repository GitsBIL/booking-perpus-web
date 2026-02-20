<?php
// Cek session, kalau belum mulai, kita mulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi 1: Cek apakah user sudah login?
function checkLogin() {
    global $base_url;
    // Kalau status bukan 'login', tendang ke halaman login
    if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
        echo "<script>alert('Eits, login dulu bos!'); window.location.href='" . $base_url . "pages/auth/login.php';</script>";
        exit();
    }
}

// Fungsi 2: Cek Jabatan (Admin atau Anggota)
function checkRole($allowed_role) {
    if ($_SESSION['role'] != $allowed_role) {
        die("403 - Forbidden. Anda tidak punya akses ke halaman ini.");
    }
}
?>