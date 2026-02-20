<?php
session_start();

// Cek Security
if($_SESSION['status'] != "login" || $_SESSION['role'] != "admin"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../../config/koneksi.php';

// Default Tanggal (Bulan ini)
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

// Hitung Statistik Periode Ini
$q_sum = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT 
        SUM(denda) as total_denda, 
        COUNT(*) as total_transaksi 
    FROM peminjaman 
    WHERE tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));

$total_pendapatan = $q_sum['total_denda'] ?? 0;
$total_transaksi = $q_sum['total_transaksi'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Perpustakaan</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php include '../components/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../components/navbar.php'; ?>
            
            <div class="container-fluid px-4 py-4">
                
                <h4 class="fw-bold mb-4" style="color: var(--wood-dark);">📊 Rekapitulasi & Pelaporan</h4>

                <div class="row g-4">
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Filter Laporan</h6>
                            </div>
                            <div class="card-body">
                                <form action="" method="GET">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                                        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal; ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                                        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir; ?>">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-2"></i> Tampilkan Data
                                        </button>
                                        <button type="button" class="btn btn-outline-dark" onclick="window.print()">
                                            <i class="bi bi-printer me-2"></i> Cetak Laporan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="card bg-primary text-white p-3 border-0 shadow-sm">
                                    <small class="text-white-50 text-uppercase fw-bold">Transaksi Periode Ini</small>
                                    <h3 class="mb-0 fw-bold"><?= $total_transaksi; ?></h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-success text-white p-3 border-0 shadow-sm">
                                    <small class="text-white-50 text-uppercase fw-bold">Total Denda Masuk</small>
                                    <h3 class="mb-0 fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-bold">Detail Laporan</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3">Peminjam</th>
                                                <th>Buku</th>
                                                <th class="text-center">Pinjam</th>
                                                <th class="text-center">Kembali</th>
                                                <th class="text-end pe-4">Denda</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = mysqli_query($koneksi, "
                                                SELECT p.*, u.nama_lengkap, b.judul_buku 
                                                FROM peminjaman p
                                                JOIN users u ON p.id_user = u.id_user
                                                JOIN buku b ON p.id_buku = b.id_buku
                                                WHERE p.tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'
                                                ORDER BY p.id_peminjaman DESC
                                            ");

                                            if(mysqli_num_rows($query) > 0) {
                                                while ($row = mysqli_fetch_assoc($query)) {
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-dark"><?= $row['nama_lengkap']; ?></td>
                                                <td><?= $row['judul_buku']; ?></td>
                                                <td class="text-center small"><?= date('d/m/y', strtotime($row['tgl_pinjam'])); ?></td>
                                                <td class="text-center small">
                                                    <?= $row['tgl_kembali'] ? date('d/m/y', strtotime($row['tgl_kembali'])) : '-'; ?>
                                                </td>
                                                <td class="text-end pe-4 fw-bold text-danger">
                                                    <?= $row['denda'] > 0 ? 'Rp '.number_format($row['denda'],0,',','.') : '-'; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Tidak ada data pada periode ini.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/script.js"></script>
</body>
</html>