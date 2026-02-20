<?php
// File: pages/auth/buat_admin.php
include '../../config/koneksi.php';

// --- DATA ADMIN BARU ---
$nama  = "Super Admin";
$nis   = "99999999";      // Gunakan NIS yang unik (misal: 9 semua)
$pass  = "admin123";      // Password terserah kamu
$role  = "admin";

// 1. Enkripsi Password (WAJIB!)
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);

// 2. Cek dulu apakah NIS ini sudah ada?
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE nomor_identitas = '$nis'");

if(mysqli_num_rows($cek) > 0){
    echo "<h1>Gagal! Akun dengan NIS $nis sudah ada.</h1>";
} else {
    // 3. Masukkan ke Database
    $query = "INSERT INTO users (nama_lengkap, nomor_identitas, password, role) 
              VALUES ('$nama', '$nis', '$pass_hash', '$role')";

    if(mysqli_query($koneksi, $query)){
        echo "<h1>Sukses! Akun Admin berhasil dibuat.</h1>";
        echo "<p>NIS: $nis</p>";
        echo "<p>Password: $pass</p>";
        echo "<br><a href='login.php'>Klik disini untuk Login</a>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>