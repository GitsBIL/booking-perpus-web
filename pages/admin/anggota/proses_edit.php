<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['update'])) {
    $id     = $_POST['id_user'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $status = $_POST['status'];
    $pass   = $_POST['password'];

    if ($pass != "") {
        // Kalau password diisi, update password juga
        $pass_md5 = md5($pass);
        $query = "UPDATE users SET nama_lengkap='$nama', status='$status', password='$pass_md5' WHERE id_user='$id'";
    } else {
        // Kalau kosong, update data diri saja
        $query = "UPDATE users SET nama_lengkap='$nama', status='$status' WHERE id_user='$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data anggota diperbarui!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update!'); window.history.back();</script>";
    }
}
?>