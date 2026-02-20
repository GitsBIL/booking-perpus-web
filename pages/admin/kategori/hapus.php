<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus data
    $query = "DELETE FROM kategori WHERE id_kategori = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        // Biasanya gagal kalau kategori ini masih dipakai di tabel buku
        echo "<script>alert('Gagal! Kategori ini mungkin sedang dipakai di data buku.'); window.location.href='index.php';</script>";
    }
}
?>