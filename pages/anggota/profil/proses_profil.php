<?php
// Cek dulu, kalau session belum ada baru start. Kalau sudah ada, abaikan.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../../../config/koneksi.php';

// Pastikan user login
if(!isset($_SESSION['id_user'])){
    header("location:../../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$nama_lengkap = $_POST['nama_lengkap'];
$password_baru = $_POST['password'];

// 1. Logika Update Password
if(!empty($password_baru)){
    // Jika user isi password baru, kita update passwordnya (tanpa hash sesuai request sebelumnya, atau pakai md5/password_hash jika mau)
    // Disini saya pakai plain text sesuai login awal kamu, kalau mau aman pakai password_hash($password_baru, PASSWORD_DEFAULT)
    $query = "UPDATE users SET nama_lengkap='$nama_lengkap', password='$password_baru' WHERE id_user='$id_user'";
} else {
    // Jika password kosong, jangan ubah password
    $query = "UPDATE users SET nama_lengkap='$nama_lengkap' WHERE id_user='$id_user'";
}

$update = mysqli_query($koneksi, $query);

if($update){
    // Update Session Nama agar tampilan di pojok kanan atas langsung berubah
    $_SESSION['nama'] = $nama_lengkap;
    
    echo "<script>
            alert('Profil berhasil diperbarui!');
            window.location='index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal memperbarui profil: " . mysqli_error($koneksi) . "');
            window.location='index.php';
          </script>";
}
?>