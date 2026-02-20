<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Kategori Baru</title>
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
                
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Kategori</a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Tambah Baru</li>
                    </ol>
                </nav>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-bold" style="color: var(--wood-dark);">
                                    <i class="bi bi-folder-plus me-2"></i> Input Genre / Kategori
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                
                                <form action="proses_tambah.php" method="POST">
                                    
                                    <div class="text-center mb-4">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                            <i class="bi bi-tags text-secondary fs-1"></i>
                                        </div>
                                        <p class="text-muted small mt-2">Masukkan nama kategori buku baru (Contoh: Sains, Novel, Sejarah)</p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">NAMA KATEGORI</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light"><i class="bi bi-bookmark-star"></i></span>
                                            <input type="text" name="nama_kategori" class="form-control" placeholder="Ketik disini..." required autofocus>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-between pt-2">
                                        <a href="index.php" class="btn btn-light border px-4">
                                            <i class="bi bi-arrow-left me-2"></i> Batal
                                        </a>
                                        <button type="submit" name="simpan" class="btn btn-primary px-5 fw-bold">
                                            <i class="bi bi-check-lg me-2"></i> SIMPAN KATEGORI
                                        </button>
                                    </div>

                                </form>

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