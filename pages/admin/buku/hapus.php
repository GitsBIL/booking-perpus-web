<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar dulu sebelum dihapus datanya
    $query_gambar = mysqli_query($koneksi, "SELECT gambar FROM buku WHERE id_buku = '$id'");
    $data_gambar  = mysqli_fetch_assoc($query_gambar);
    $file_gambar  = $data_gambar['gambar'];

    // 2. Hapus Data dari Database
    $hapus = mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = '$id'");

    if ($hapus) {
        // 3. Hapus File Fisik di folder uploads (Kecuali default.jpg)
        if ($file_gambar != 'default.jpg') {
            $path = "../../../uploads/" . $file_gambar;
            if (file_exists($path)) {
                unlink($path); // unlink = delete file
            }
        }
        
        echo "<script>alert('Buku berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku!'); window.location.href='index.php';</script>";
    }
}
?>