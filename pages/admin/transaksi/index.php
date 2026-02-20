<?php
session_start();

// Cek Security
if($_SESSION['status'] != "login" || $_SESSION['role'] != "admin"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../../config/koneksi.php';

// Hitung Ringkasan
$q_pinjam  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman = 'dipinjam'"));
$q_request = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman = 'diajukan'"));
$q_telat   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE status_peminjaman = 'dipinjam' AND tgl_jatuh_tempo < CURDATE()"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sirkulasi Peminjaman</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <style>
        .nav-pills .nav-link.active {
            background-color: var(--wood-dark);
            color: #fff;
        }
        .nav-pills .nav-link {
            color: #6c757d;
            font-weight: 500;
        }
        .row-overdue { background-color: #fff5f5; }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php include '../components/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../components/navbar.php'; ?>
            
            <div class="container-fluid px-4 py-4">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-6">
                        <h4 class="fw-bold mb-1" style="color: var(--wood-dark);">📋 Sirkulasi Peminjaman</h4>
                        <p class="text-muted small mb-0">Kelola arus keluar-masuk buku perpustakaan.</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <span class="badge bg-white border text-dark p-2 me-1 shadow-sm"><i class="bi bi-journal-arrow-up text-primary"></i> Dipinjam: <?= $q_pinjam['total']; ?></span>
                        <span class="badge bg-white border text-dark p-2 me-1 shadow-sm"><i class="bi bi-hourglass-split text-warning"></i> Request: <?= $q_request['total']; ?></span>
                        <span class="badge bg-danger text-white p-2 shadow-sm"><i class="bi bi-exclamation-circle"></i> Telat: <?= $q_telat['total']; ?></span>
                    </div>
                </div>

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item"><button class="nav-link active rounded-pill px-4" onclick="filterTable('all', this)">Semua</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill px-4" onclick="filterTable('Request', this)">Permintaan Baru</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill px-4" onclick="filterTable('Dipinjam', this)">Sedang Dipinjam</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill px-4" onclick="filterTable('Selesai', this)">Riwayat Selesai</button></li>
                </ul>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="transaksiTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Detail Peminjam</th>
                                        <th>Buku</th>
                                        <th>Periode Pinjam</th>
                                        <th>Status</th>
                                        <th>Denda</th> <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($koneksi, "SELECT p.*, u.nama_lengkap, b.judul_buku FROM peminjaman p JOIN users u ON p.id_user = u.id_user JOIN buku b ON p.id_buku = b.id_buku ORDER BY p.id_peminjaman DESC");
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        $status = $row['status_peminjaman'];
                                        // Cek Keterlambatan
                                        $is_overdue = ($status == 'dipinjam' && $row['tgl_jatuh_tempo'] < date('Y-m-d'));
                                        $row_style = $is_overdue ? 'row-overdue border-start border-4 border-danger' : '';
                                    ?>
                                    <tr class="<?= $row_style; ?>">
                                        <td class="ps-4 py-3">
                                            <span class="d-block fw-bold text-dark"><?= $row['nama_lengkap']; ?></span>
                                            <small class="text-muted font-monospace bg-light border px-1 rounded"><?= $row['kode_transaksi']; ?></small>
                                        </td>
                                        <td><?= $row['judul_buku']; ?></td>
                                        <td>
                                            <small class="d-block text-muted">Pinjam: <?= date('d/m/y', strtotime($row['tgl_pengajuan'])); ?></small>
                                            <?php if($row['tgl_jatuh_tempo']): ?>
                                                <small class="d-block <?= $is_overdue ? 'text-danger fw-bold' : 'text-primary'; ?>">
                                                    Tempo: <?= date('d/m/y', strtotime($row['tgl_jatuh_tempo'])); ?>
                                                    <?= $is_overdue ? '<i class="bi bi-exclamation-triangle-fill ms-1"></i>' : ''; ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status-hidden d-none">
                                                <?php 
                                                    if($status == 'diajukan') echo "Request";
                                                    elseif($status == 'dipinjam') echo "Dipinjam";
                                                    elseif($status == 'kembali') echo "Selesai";
                                                    else echo "Ditolak";
                                                ?>
                                            </span>

                                            <?php if($status == 'diajukan'): ?> 
                                                <span class="badge bg-warning text-dark border border-warning"><i class="bi bi-hourglass-split"></i> Menunggu ACC</span>
                                            <?php elseif($status == 'dipinjam'): ?> 
                                                <span class="badge bg-primary"><i class="bi bi-journal-arrow-up"></i> Dipinjam</span>
                                            <?php elseif($status == 'kembali'): ?> 
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                            <?php else: ?> 
                                                <span class="badge bg-secondary">Ditolak</span> 
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <?php if($row['denda'] > 0): ?>
                                                <span class="text-danger fw-bold">Rp <?= number_format($row['denda'], 0, ',', '.'); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end pe-4">
                                            <?php if($status == 'diajukan'): ?>
                                                <div class="btn-group shadow-sm">
                                                    <a href="proses_persetujuan.php?id=<?= $row['id_peminjaman']; ?>&aksi=setujui&id_buku=<?= $row['id_buku']; ?>" class="btn btn-sm btn-success fw-bold" title="Setujui"><i class="bi bi-check-lg"></i></a>
                                                    <a href="proses_persetujuan.php?id=<?= $row['id_peminjaman']; ?>&aksi=tolak" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak pengajuan?')" title="Tolak"><i class="bi bi-x-lg"></i></a>
                                                </div>
                                            <?php elseif($status == 'dipinjam'): ?>
                                                <a href="../../anggota/transaksi/proses_kembali.php?id=<?= $row['id_peminjaman']; ?>" class="btn btn-sm btn-outline-primary" onclick="return confirm('Proses pengembalian buku ini (Admin)?')">
                                                    <i class="bi bi-box-arrow-in-down-left me-1"></i> Kembali
                                                </a>
                                            <?php else: ?>
                                                <i class="bi bi-check-all text-success fs-5"></i>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
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
    <script src="../../../assets/js/script.js"></script>
    <script>
        function filterTable(status, btn) {
            document.querySelectorAll('.nav-link').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            let rows = document.querySelectorAll('#transaksiTable tbody tr');
            rows.forEach(row => {
                let rowStatus = row.querySelector('.status-hidden').textContent.trim();
                if(status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>