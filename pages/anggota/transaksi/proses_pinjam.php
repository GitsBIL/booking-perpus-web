<?php
session_start();
include '../../../config/koneksi.php';

// Pastikan tidak ada output sebelum HTML
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Peminjaman</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: sans-serif; background-color: #fdfbf7; }
    </style>
</head>
<body>

<?php
// 1. Cek Login
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    echo "<script>window.location='../../auth/login.php?pesan=belum_login';</script>";
    exit();
}

if($_POST){
    $id_user = $_SESSION['id_user'];
    $id_buku = $_POST['id_buku'];
    
    // 2. Persiapan Data
    $kode_transaksi = "TRX-" . time(); 
    $tgl_pinjam = date('Y-m-d');
    $tgl_jatuh_tempo = date('Y-m-d', strtotime('+7 days', strtotime($tgl_pinjam))); 
    $status = 'dipinjam'; 

    // 3. Cek Stok Dulu
    $cek_stok = mysqli_query($koneksi, "SELECT stok_tersedia, judul_buku FROM buku WHERE id_buku='$id_buku'");
    $data_buku = mysqli_fetch_assoc($cek_stok);

    if($data_buku['stok_tersedia'] > 0){
        
        // 4. Kurangi Stok
        $kurang_stok = mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id_buku='$id_buku'");

        // 5. Masukkan ke Tabel Peminjaman
        $query_pinjam = mysqli_query($koneksi, "INSERT INTO peminjaman 
            (kode_transaksi, id_user, id_buku, tgl_pinjam, tgl_jatuh_tempo, status_peminjaman) 
            VALUES 
            ('$kode_transaksi', '$id_user', '$id_buku', '$tgl_pinjam', '$tgl_jatuh_tempo', '$status')");

        if($query_pinjam && $kurang_stok){
            echo "<script>
                Swal.fire({
                    title: 'Berhasil Meminjam!',
                    html: 'Buku <b>" . $data_buku['judul_buku'] . "</b> berhasil dipinjam.<br>Kode Transaksi: <b>$kode_transaksi</b>',
                    icon: 'success',
                    confirmButtonText: 'Lihat Riwayat',
                    confirmButtonColor: '#D35400'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'riwayat.php';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Gagal',
                    text: 'Terjadi kesalahan database.',
                    icon: 'error'
                }).then(() => {
                    window.location = '../katalog.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Stok Habis!',
                text: 'Maaf, buku ini sedang tidak tersedia.',
                icon: 'warning',
                confirmButtonColor: '#D35400'
            }).then(() => {
                window.location = '../katalog.php';
            });
        </script>";
    }
}
?>

</body>
</html>