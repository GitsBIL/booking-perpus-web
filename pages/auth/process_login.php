<?php
session_start();
// Mundur 2 folder untuk ambil koneksi
include '../../config/koneksi.php';

if (isset($_POST['login'])) {
    $nip_nim  = mysqli_real_escape_string($koneksi, $_POST['nomor_identitas']);
    $password = md5($_POST['password']); // Password di database kita enkripsi pake MD5

    // Cari data user
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE nomor_identitas = '$nip_nim' AND password = '$password'");
    $cek   = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);

        // Simpan identitas user ke Session (biar server ingat siapa yang login)
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama']    = $data['nama_lengkap'];
        $_SESSION['role']    = $data['role'];
        $_SESSION['status']  = "login";

        // Arahkan sesuai jabatan
        if ($data['role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } else if ($data['role'] == "anggota") {
            header("Location: ../anggota/dashboard.php");
        }
    } else {
        // Balikin ke login kalau salah
        header("Location: login.php?pesan=gagal");
    }
}
?>