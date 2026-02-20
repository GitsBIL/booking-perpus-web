<?php
$server = "localhost";
$user   = "root";
$pass   = "";
$db     = "db_perpus_web"; // Pastikan nama ini SAMA PERSIS dengan database kamu

$koneksi = mysqli_connect($server, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Set Zona Waktu Indonesia Barat (Biar jam pinjam buku akurat)
date_default_timezone_set('Asia/Jakarta');

// Base URL (Alamat dasar website, ganti jika nama folder bukan 'PerpusDigital')
$base_url = "http://localhost/PerpusDigital/";
?>