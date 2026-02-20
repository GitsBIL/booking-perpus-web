<?php
// Pastikan path ke koneksi benar. 
// Karena posisi file ini ada di pages/auth/, maka kita perlu keluar 2 folder (../../)
include '../../config/koneksi.php';

// Menangkap data yang dikirim dari form register
$nama = $_POST['nama'];
$nis  = $_POST['nomor_identitas'];
$pass = $_POST['password'];
$role = 'anggota'; // Default role pasti anggota

// 1. Cek apakah NIS sudah terdaftar?
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE nomor_identitas = '$nis'");

if(mysqli_num_rows($cek) > 0) {
    // Jika NIS sudah ada, tolak!
    echo "<script>
            alert('Pendaftaran Gagal! Nomor Identitas (NIS) sudah terdaftar.');
            window.location='register.php';
          </script>";
} else {
    // 2. Enkripsi Password (Hashing)
    // Ini PENTING agar login.php (yang pakai password_verify) bisa membaca password ini
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    // 3. Masukkan ke Database
    $query = "INSERT INTO users (nama_lengkap, nomor_identitas, password, role) 
              VALUES ('$nama', '$nis', '$password_hash', '$role')";

    if(mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Selamat! Akun berhasil dibuat. Silakan Login.');
                window.location='login.php';
              </script>";
    } else {
        echo "<script>
                alert('Error Sistem: " . mysqli_error($koneksi) . "');
                window.location='register.php';
              </script>";
    }
}
?>