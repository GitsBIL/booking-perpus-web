<?php
session_start();
include '../../../config/koneksi.php';

// Pastikan kode ini TIDAK ada output HTML sebelum DOCTYPE di bawah
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php

if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../../auth/login.php");
    exit();
}

$id_peminjaman = $_GET['id'];
$tgl_kembali_aktual = date('Y-m-d'); 

$cek_transaksi = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
$data = mysqli_fetch_array($cek_transaksi);

if($data['status_peminjaman'] == 'dipinjam'){
    
    $id_buku = $data['id_buku'];
    $tgl_jatuh_tempo = $data['tgl_jatuh_tempo'];

    $denda = 0;
    $tarif_denda_per_hari = 1000; 

    if($tgl_kembali_aktual > $tgl_jatuh_tempo){
        $tgl1 = new DateTime($tgl_jatuh_tempo);
        $tgl2 = new DateTime($tgl_kembali_aktual);
        $selisih = $tgl2->diff($tgl1);
        $jumlah_hari_telat = $selisih->days;
        $denda = $jumlah_hari_telat * $tarif_denda_per_hari;
    }

    $update_transaksi = mysqli_query($koneksi, "UPDATE peminjaman SET status_peminjaman = 'kembali', tgl_kembali = '$tgl_kembali_aktual', denda = '$denda' WHERE id_peminjaman = '$id_peminjaman'");
    $update_stok = mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia + 1 WHERE id_buku = '$id_buku'");

    if($update_transaksi && $update_stok){
        $pesan = ($denda > 0) ? "Terlambat $jumlah_hari_telat hari. Denda: Rp " . number_format($denda,0,',','.') : "Terima kasih telah mengembalikan buku tepat waktu.";
        $icon = ($denda > 0) ? "warning" : "success";
        
        echo "<script>
            Swal.fire({
                title: 'Berhasil Dikembalikan!',
                text: '$pesan',
                icon: '$icon'
            }).then(() => {
                window.location = 'riwayat.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal',
                text: 'Terjadi kesalahan sistem.',
                icon: 'error'
            }).then(() => {
                window.location = 'riwayat.php';
            });
        </script>";
    }

} else {
    echo "<script>
        Swal.fire({
            title: 'Info',
            text: 'Transaksi ini sudah selesai sebelumnya.',
            icon: 'info'
        }).then(() => {
            window.location = 'riwayat.php';
        });
    </script>";
}
?>
</body>
</html>