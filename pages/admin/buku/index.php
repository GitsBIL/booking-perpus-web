<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// Statistik Ringkas untuk Header
$q_total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"));
$q_stok  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(stok_tersedia) as total FROM buku"));
$q_low   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE stok_tersedia <= 2"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog Buku - Admin</title>
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
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-white border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-journal-bookmark-fill text-primary fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted text-uppercase small mb-1">Total Judul</h6>
                                <h4 class="mb-0 fw-bold text-dark"><?= $q_total['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-box-seam-fill text-success fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted text-uppercase small mb-1">Total Eksemplar</h6>
                                <h4 class="mb-0 fw-bold text-dark"><?= $q_stok['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-white border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-exclamation-circle-fill text-danger fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted text-uppercase small mb-1">Stok Menipis</h6>
                                <h4 class="mb-0 fw-bold text-dark"><?= $q_low['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--wood-dark);">📚 Data Koleksi Buku</h5>
                            <small class="text-muted">Kelola inventaris perpustakaan</small>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control bg-light border-start-0" placeholder="Cari judul/penulis...">
                            </div>
                            <a href="tambah.php" class="btn btn-primary d-flex align-items-center">
                                <i class="bi bi-plus-lg me-2"></i> Tambah Buku
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="dataTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Buku Info</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($koneksi, "SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori ORDER BY id_buku DESC");
                                    while ($row = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="../../../uploads/<?= $row['gambar']; ?>" width="45" height="60" class="rounded shadow-sm me-3 object-fit-cover">
                                                <div>
                                                    <span class="d-block fw-bold text-dark"><?= $row['judul_buku']; ?></span>
                                                    <small class="text-muted"><i class="bi bi-pen me-1"></i><?= $row['penulis']; ?> • <?= $row['penerbit']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                                                <i class="bi bi-tag me-1"></i> <?= $row['nama_kategori']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($row['stok_tersedia'] <= 2): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger"><?= $row['stok_tersedia']; ?> Sisa</span>
                                            <?php else: ?>
                                                <span class="fw-bold text-dark"><?= $row['stok_tersedia']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['id_buku']; ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                <a href="hapus.php?id=<?= $row['id_buku']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus buku ini?')" title="Hapus"><i class="bi bi-trash"></i></a>
                                            </div>
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
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#dataTable tbody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>