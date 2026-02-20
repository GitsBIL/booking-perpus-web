<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['simpan'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_buku       = $_POST['id_buku'];
    $tgl_kembali   = $_POST['tgl_kembali'];
    $denda         = $_POST['denda'];

    // 1. Update Tabel Peminjaman (Status Selesai)
    $query_pinjam = "UPDATE peminjaman SET 
                     tgl_kembali = '$tgl_kembali', 
                     denda = '$denda', 
                     status_peminjaman = 'kembali' 
                     WHERE id_peminjaman = '$id_peminjaman'";

    // 2. Update Stok Buku (Kembalikan stok)
    $query_stok = "UPDATE buku SET stok_tersedia = stok_tersedia + 1 WHERE id_buku = '$id_buku'";

    // Jalankan kedua query
    if (mysqli_query($koneksi, $query_pinjam) && mysqli_query($koneksi, $query_stok)) {
        echo "<script>alert('Buku berhasil dikembalikan! Stok bertambah.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memproses: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
    }
}
?>