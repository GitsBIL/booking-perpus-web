<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus user (Data peminjaman akan otomatis terhapus karena ON DELETE CASCADE di database)
    $query = "DELETE FROM users WHERE id_user = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Anggota berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus anggota!'); window.history.back();</script>";
    }
} else {
    // Kalau dibuka tanpa ID, balik ke index
    header("Location: index.php");
}
?>