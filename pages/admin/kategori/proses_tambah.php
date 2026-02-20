<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);

    $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        echo "<script>alert('Gagal menambah kategori!'); window.history.back();</script>";
    }
}
?>