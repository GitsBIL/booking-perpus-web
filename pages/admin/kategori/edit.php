<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// Validasi ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// Ambil Data Lama
$query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Kategori</title>
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
                        <li class="breadcrumb-item active text-dark" aria-current="page">Edit Data</li>
                    </ol>
                </nav>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-warning bg-opacity-10 py-3 border-bottom border-warning">
                                <h6 class="mb-0 fw-bold text-dark">
                                    <i class="bi bi-pencil-square me-2"></i> Perbarui Nama Kategori
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                
                                <form action="proses_edit.php" method="POST">
                                    <input type="hidden" name="id_kategori" value="<?= $data['id_kategori']; ?>">
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">NAMA KATEGORI SAAT INI</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light"><i class="bi bi-tag-fill text-warning"></i></span>
                                            <input type="text" name="nama_kategori" class="form-control fw-bold text-dark" value="<?= $data['nama_kategori']; ?>" required>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i> Perubahan nama akan otomatis terupdate di semua buku terkait.
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-between pt-2">
                                        <a href="index.php" class="btn btn-light border px-4">
                                            <i class="bi bi-arrow-left me-2"></i> Batal
                                        </a>
                                        <button type="submit" name="update" class="btn btn-warning px-5 fw-bold shadow-sm">
                                            <i class="bi bi-save me-2"></i> SIMPAN PERUBAHAN
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