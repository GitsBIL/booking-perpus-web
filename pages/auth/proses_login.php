<?php
session_start();
// Menghubungkan ke database
// Pastikan path ini benar (keluar 2 folder untuk cari folder config)
include '../../config/koneksi.php';

// Menangkap data dari form login
$nis = $_POST['nomor_identitas'];
$pass = $_POST['password'];

// 1. Cek apakah NIS ada di database?
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE nomor_identitas='$nis'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    // 2. Cek apakah Password Cocok?
    if(password_verify($pass, $data['password'])){
        
        // Buat Session Login
        $_SESSION['status'] = "login";
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama']   = $data['nama_lengkap'];
        $_SESSION['role']   = $data['role'];

        // 3. Arahkan ke Dashboard (BAGIAN INI SUDAH DIPERBAIKI)
        if($data['role'] == "admin"){
            // Jika Admin, arahkan ke folder admin (sesuaikan jika perlu)
            echo "<script>
                alert('Login Admin Berhasil!'); 
                window.location='../admin/dashboard.php'; 
            </script>";
        } else {
            // Jika Anggota, arahkan ke folder anggota
            // Path: keluar dari auth (../) -> masuk ke anggota -> dashboard.php
            echo "<script>
                alert('Login Berhasil! Selamat Datang, " . $data['nama_lengkap'] . "'); 
                window.location='../anggota/dashboard.php'; 
            </script>";
        }

    } else {
        echo "<script>
            alert('Password Salah! Silakan coba lagi.');
            window.location='login.php';
        </script>";
    }
} else {
    echo "<script>
        alert('NIS Tidak Ditemukan! Silakan daftar akun dulu.');
        window.location='login.php';
    </script>";
}
?>