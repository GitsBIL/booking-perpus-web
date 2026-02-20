<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// 1. Hitung Total Kategori
$q_total_kat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori"));

// 2. Hitung Total Buku Seluruhnya (untuk kalkulasi persentase)
$q_total_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"));
$total_buku_all = $q_total_buku['total'];
if($total_buku_all == 0) $total_buku_all = 1; // Mencegah division by zero error

// 3. Cari Kategori Terpopuler (Yang paling banyak bukunya)
$q_top = mysqli_query($koneksi, "
    SELECT k.nama_kategori, COUNT(b.id_buku) as jumlah 
    FROM kategori k 
    JOIN buku b ON k.id_kategori = b.id_kategori 
    GROUP BY k.id_kategori 
    ORDER BY jumlah DESC LIMIT 1
");
$d_top = mysqli_fetch_assoc($q_top);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Kategori</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <style>
        .cat-icon {
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 1.2rem;
        }
        .progress-slim { height: 6px; border-radius: 3px; background-color: #e9ecef; }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php include '../components/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../components/navbar.php'; ?>
            
            <div class="container-fluid px-4 py-4">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--wood-dark);">🏷️ Klasifikasi & Kategori</h4>
                        <p class="text-muted small mb-0">Atur pengelompokan koleksi buku perpustakaan.</p>
                    </div>
                    <a href="tambah.php" class="btn btn-primary shadow-sm px-4">
                        <i class="bi bi-plus-circle me-2"></i> Kategori Baru
                    </a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded me-3">
                                <i class="bi bi-tags-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted text-uppercase small mb-1">Total Genre</h6>
                                <h3 class="mb-0 fw-bold text-dark"><?= $q_total_kat['total']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded me-3">
                                <i class="bi bi-star-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted text-uppercase small mb-1">Terpopuler</h6>
                                <h4 class="mb-0 fw-bold text-dark">
                                    <?= ($d_top) ? $d_top['nama_kategori'] : '-'; ?>
                                </h4>
                                <small class="text-muted">
                                    <?= ($d_top) ? $d_top['jumlah'].' Judul Buku' : 'Belum ada data'; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-secondary">Daftar Kategori Aktif</h6>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control bg-light border-start-0" placeholder="Cari kategori...">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="dataTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3" width="5%">#</th>
                                        <th width="35%">Nama Kategori</th>
                                        <th width="30%">Distribusi Buku</th>
                                        <th class="text-center">Jumlah Buku</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query Kompleks: Join ke Tabel Buku untuk hitung jumlah
                                    $query = mysqli_query($koneksi, "
                                        SELECT k.*, COUNT(b.id_buku) as jumlah_buku 
                                        FROM kategori k 
                                        LEFT JOIN buku b ON k.id_kategori = b.id_kategori 
                                        GROUP BY k.id_kategori 
                                        ORDER BY k.nama_kategori ASC
                                    ");
                                    
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        // Hitung Persentase untuk Progress Bar
                                        $persen = ($row['jumlah_buku'] / $total_buku_all) * 100;
                                        
                                        // Warna Progress Bar dinamis
                                        if($persen > 20) $color = 'bg-success';
                                        elseif($persen > 10) $color = 'bg-primary';
                                        else $color = 'bg-secondary';
                                    ?>
                                    <tr>
                                        <td class="ps-4 text-muted small"><?= $no++; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="cat-icon bg-light text-secondary me-3">
                                                    <i class="bi bi-folder2-open"></i>
                                                </div>
                                                <span class="fw-bold text-dark"><?= $row['nama_kategori']; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-slim w-100 me-2">
                                                    <div class="progress-bar <?= $color; ?>" style="width: <?= $persen; ?>%"></div>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.7rem; width: 35px;"><?= number_format($persen, 1); ?>%</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($row['jumlah_buku'] > 0): ?>
                                                <span class="badge bg-white border text-dark fw-normal">
                                                    <?= $row['jumlah_buku']; ?> Judul
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted">Kosong</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-outline-secondary" title="Edit Nama"><i class="bi bi-pencil"></i></a>
                                                
                                                <?php $pesan_hapus = ($row['jumlah_buku'] > 0) ? "PERINGATAN: Kategori ini memiliki {$row['jumlah_buku']} buku. Jika dihapus, buku tersebut akan kehilangan kategorinya. Tetap hapus?" : "Hapus kategori ini?"; ?>
                                                
                                                <a href="hapus.php?id=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= $pesan_hapus; ?>')" title="Hapus"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Data diperbarui secara real-time.</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/script.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#dataTable tbody tr');
            rows.forEach(row => {
                let text = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>