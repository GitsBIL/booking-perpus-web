<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['simpan'])) {
    $nip      = mysqli_real_escape_string($koneksi, $_POST['nomor_identitas']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password = md5($_POST['password']); // Enkripsi MD5
    $role     = 'anggota'; // Otomatis jadi anggota

    // Cek apakah NIP sudah dipakai?
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE nomor_identitas = '$nip'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIP/NIM sudah terdaftar! Gunakan yang lain.'); window.history.back();</script>";
        exit();
    }

    $query = "INSERT INTO users (nomor_identitas, nama_lengkap, password, role, status) 
              VALUES ('$nip', '$nama', '$password', '$role', 'aktif')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Anggota berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
    }
}
?>