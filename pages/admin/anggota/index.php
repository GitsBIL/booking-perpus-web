<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// --- LOGIKA STATISTIK ---
$q_total  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"));
$q_aktif  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE status = 'aktif'"));
$q_non    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE status = 'nonaktif'"));
$q_admin  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role = 'admin'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Anggota</title>
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
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--wood-dark);">👥 Data Pengguna Sistem</h4>
                        <p class="text-muted small mb-0">Kelola akses admin dan keanggotaan perpustakaan.</p>
                    </div>
                    <a href="tambah.php" class="btn btn-primary shadow-sm px-4">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah User Baru
                    </a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-primary">
                            <small class="text-muted text-uppercase fw-bold">Total User</small>
                            <h3 class="mb-0 fw-bold text-dark"><?= $q_total['total']; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-success">
                            <small class="text-muted text-uppercase fw-bold">Status Aktif</small>
                            <h3 class="mb-0 fw-bold text-success"><?= $q_aktif['total']; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-danger">
                            <small class="text-muted text-uppercase fw-bold">Non-Aktif / Suspend</small>
                            <h3 class="mb-0 fw-bold text-danger"><?= $q_non['total']; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-dark">
                            <small class="text-muted text-uppercase fw-bold">Administrator</small>
                            <h3 class="mb-0 fw-bold text-dark"><?= $q_admin['total']; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-secondary">Direktori Pengguna</h6>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control bg-light border-start-0" placeholder="Cari nama / NIP...">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="dataTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Profil Pengguna</th>
                                        <th>Role / Jabatan</th>
                                        <th>Status Akun</th>
                                        <th>Terdaftar Sejak</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC, status ASC");
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        // Generate Avatar
                                        $initial = urlencode($row['nama_lengkap']);
                                        $bg = ($row['role'] == 'admin') ? '3E2723' : 'A16E4B'; 
                                    ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?= $initial; ?>&background=<?= $bg; ?>&color=fff&size=45" class="rounded-circle me-3 shadow-sm">
                                                <div>
                                                    <span class="d-block fw-bold text-dark"><?= $row['nama_lengkap']; ?></span>
                                                    <small class="text-muted font-monospace bg-light px-1 rounded border">ID: <?= $row['nomor_identitas']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($row['role'] == 'admin'): ?>
                                                <span class="badge bg-dark text-white shadow-sm"><i class="bi bi-shield-lock-fill me-1"></i> ADMIN</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border"><i class="bi bi-person me-1"></i> Anggota</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($row['status'] == 'aktif'): ?>
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 fw-bold">● Aktif</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 fw-bold">● Non-Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($row['created_at'])); ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="edit.php?id=<?= $row['id_user']; ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                                <?php if($_SESSION['id_user'] != $row['id_user']): ?>
                                                    <a href="hapus.php?id=<?= $row['id_user']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus user ini?')" title="Hapus"><i class="bi bi-trash"></i></a>
                                                <?php endif; ?>
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
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>