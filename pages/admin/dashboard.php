<?php
session_start();

// 1. Cek Keamanan (Harus Login & Role Admin)
if($_SESSION['status'] != "login" || $_SESSION['role'] != "admin"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../config/koneksi.php';

// --- QUERY DATA STATISTIK REAL-TIME ---
// Hitung Total Buku
$q_buku = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
$d_buku = mysqli_fetch_assoc($q_buku);

// Hitung Anggota Aktif
$q_anggota = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='anggota'");
$d_anggota = mysqli_fetch_assoc($q_anggota);

// Hitung Sedang Dipinjam
$q_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman='dipinjam'");
$d_pinjam = mysqli_fetch_assoc($q_pinjam);

// Hitung Sudah Kembali/Selesai
$q_kembali = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman='kembali'");
$d_kembali = mysqli_fetch_assoc($q_kembali);

// Ambil 5 Transaksi Terbaru (Untuk Tabel Ringkasan)
$q_latest = mysqli_query($koneksi, "
    SELECT p.*, u.nama_lengkap, b.judul_buku 
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN buku b ON p.id_buku = b.id_buku
    ORDER BY p.id_peminjaman DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        /* Styling Khusus Card Admin */
        .stat-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(62, 39, 35, 0.1);
        }
        .stat-icon-bg {
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 8rem;
            opacity: 0.05;
            color: var(--wood-dark);
            transform: rotate(-15deg);
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--wood-dark);
            font-family: 'Merriweather', serif;
        }
        .stat-label {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include 'components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <?php include 'components/navbar.php'; ?>

        <div class="container-fluid p-4">
            
            <h4 class="mb-4 fw-bold" style="color: var(--wood-dark); font-family: 'Merriweather', serif;">
                Ringkasan Pustaka
            </h4>

            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="stat-card border-start border-4 border-primary">
                        <i class="bi bi-book stat-icon-bg"></i>
                        <div class="stat-value"><?php echo $d_buku['total']; ?></div>
                        <div class="stat-label text-primary">Total Buku</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card border-start border-4 border-success">
                        <i class="bi bi-people stat-icon-bg"></i>
                        <div class="stat-value"><?php echo $d_anggota['total']; ?></div>
                        <div class="stat-label text-success">Anggota Aktif</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card border-start border-4 border-warning">
                        <i class="bi bi-hourglass-split stat-icon-bg"></i>
                        <div class="stat-value"><?php echo $d_pinjam['total']; ?></div>
                        <div class="stat-label text-warning">Sedang Dipinjam</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card border-start border-4 border-secondary">
                        <i class="bi bi-check-circle stat-icon-bg"></i>
                        <div class="stat-value"><?php echo $d_kembali['total']; ?></div>
                        <div class="stat-label text-secondary">Riwayat Selesai</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold" style="color: var(--wood-dark);">Aktivitas Terbaru</h6>
                    <a href="transaksi/index.php" class="btn btn-sm btn-outline-dark rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Peminjam</th>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($q_latest) > 0) { 
                                    while($row = mysqli_fetch_array($q_latest)){ ?>
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                        <small class="text-muted">TRX-<?php echo $row['id_peminjaman']; ?></small>
                                    </td>
                                    <td><?php echo $row['judul_buku']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                                    <td>
                                        <?php if($row['status_peminjaman'] == 'dipinjam') { ?>
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Kembali</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>