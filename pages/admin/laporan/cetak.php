<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Laporan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2, h4 { text-align: center; margin: 0; }
        .header { margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* CSS Khusus Print - Sembunyikan tombol saat dicetak */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>PERPUSTAKAAN DIGITAL</h2>
        <h4>Laporan Peminjaman & Pengembalian Buku</h4>
        <p class="text-center small">Periode: <?= $_GET['tgl_awal']; ?> s/d <?= $_GET['tgl_akhir']; ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tgl_awal  = $_GET['tgl_awal'];
            $tgl_akhir = $_GET['tgl_akhir'];

            $query = mysqli_query($koneksi, "
                SELECT p.*, u.nama_lengkap, b.judul_buku 
                FROM peminjaman p
                JOIN users u ON p.id_user = u.id_user
                JOIN buku b ON p.id_buku = b.id_buku
                WHERE p.tgl_pengajuan BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY p.id_peminjaman ASC
            ");

            $no = 1;
            $total_denda = 0;
            while ($row = mysqli_fetch_assoc($query)) {
                $total_denda += $row['denda'];
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= $row['nama_lengkap']; ?></td>
                <td><?= $row['judul_buku']; ?></td>
                <td class="text-center"><?= $row['tgl_pinjam']; ?></td>
                <td class="text-center"><?= $row['tgl_kembali']; ?></td>
                <td class="text-right">Rp <?= number_format($row['denda'], 0, ',', '.'); ?></td>
                <td class="text-center"><?= strtoupper($row['status_peminjaman']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL DENDA</th>
                <th class="text-right">Rp <?= number_format($total_denda, 0, ',', '.'); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Bekasi, <?= date('d-m-Y'); ?></p>
        <br><br><br>
        <p>( Administrator )</p>
    </div>

</body>
</html>