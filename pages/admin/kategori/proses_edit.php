<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['update'])) {
    $id   = $_POST['id_kategori'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);

    $query = "UPDATE kategori SET nama_kategori = '$nama' WHERE id_kategori = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        echo "<script>alert('Gagal update kategori!'); window.history.back();</script>";
    }
}
?>