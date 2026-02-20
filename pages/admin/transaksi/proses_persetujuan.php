<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// Pastikan ada parameter ID dan Aksi
if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id_pinjam = $_GET['id'];
    $aksi      = $_GET['aksi'];

    if ($aksi == 'setujui') {
        $id_buku = $_GET['id_buku'];
        
        // 1. Cek Stok Dulu (Jaga-jaga kalau stok habis saat user klik request)
        $cek_stok = mysqli_query($koneksi, "SELECT stok_tersedia FROM buku WHERE id_buku = '$id_buku'");
        $data_stok = mysqli_fetch_assoc($cek_stok);

        if ($data_stok['stok_tersedia'] > 0) {
            // 2. Set Tanggal
            $tgl_pinjam = date('Y-m-d');
            $tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days', strtotime($tgl_pinjam))); // Pinjam 7 Hari

            // 3. Update Status Peminjaman
            $query_update = "UPDATE peminjaman SET 
                             tgl_pinjam = '$tgl_pinjam', 
                             tgl_jatuh_tempo = '$tgl_jatuh_tempo', 
                             status_peminjaman = 'dipinjam' 
                             WHERE id_peminjaman = '$id_pinjam'";
            
            // 4. Kurangi Stok Buku
            $query_kurang_stok = "UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id_buku = '$id_buku'";

            if (mysqli_query($koneksi, $query_update) && mysqli_query($koneksi, $query_kurang_stok)) {
                echo "<script>alert('Peminjaman DISETUJUI! Stok buku berkurang.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
            }

        } else {
            echo "<script>alert('Gagal! Stok buku habis.'); window.location.href='index.php';</script>";
        }

    } elseif ($aksi == 'tolak') {
        // Kalau ditolak, cuma ganti status aja, stok gak berubah
        $query = "UPDATE peminjaman SET status_peminjaman = 'ditolak' WHERE id_peminjaman = '$id_pinjam'";
        mysqli_query($koneksi, $query);
        echo "<script>alert('Pengajuan DITOLAK.'); window.location.href='index.php';</script>";
    }
}
?>