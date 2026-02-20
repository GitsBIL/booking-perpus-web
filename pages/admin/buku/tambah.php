<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Buku Baru</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <style>
        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            background: #faf8f5;
            transition: 0.3s;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: var(--copper-accent);
            background: #fff;
        }
        .form-label {
            font-weight: 600;
            color: var(--wood-dark);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php include '../components/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../components/navbar.php'; ?>
            
            <div class="container-fluid px-4 py-4">
                
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Katalog</a></li>
                                <li class="breadcrumb-item active text-dark" aria-current="page">Tambah Buku</li>
                            </ol>
                        </nav>
                        <h4 class="fw-bold mb-0" style="color: var(--wood-dark);">📖 Input Koleksi Baru</h4>
                    </div>
                </div>

                <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Informasi Pustaka</h6>
                                </div>
                                <div class="card-body p-4">
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                        <input type="text" name="judul_buku" class="form-control form-control-lg" placeholder="Masukkan judul lengkap..." required>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Kategori</label>
                                            <select name="id_kategori" class="form-select" required>
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <?php
                                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                                while ($k = mysqli_fetch_assoc($kat)) {
                                                    echo "<option value='{$k['id_kategori']}'>{$k['nama_kategori']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Penulis / Pengarang</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                                <input type="text" name="penulis" class="form-control" placeholder="Nama penulis" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Penerbit</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                                <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tahun Terbit</label>
                                            <input type="number" name="tahun_terbit" class="form-control" placeholder="Contoh: 2024" min="1900" max="<?= date('Y'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Stok Awal</label>
                                        <div class="input-group w-50">
                                            <span class="input-group-text bg-light">Jumlah</span>
                                            <input type="number" name="stok_tersedia" class="form-control" value="1" min="1" required>
                                        </div>
                                        <small class="text-muted">Jumlah eksemplar buku yang tersedia untuk dipinjam.</small>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2"></i>Sampul Buku</h6>
                                </div>
                                <div class="card-body p-4 text-center">
                                    
                                    <div class="upload-area p-4 mb-3" onclick="document.getElementById('fileInput').click()">
                                        <img id="preview" src="../../../assets/img/icon_PerpusDigital.png" class="img-fluid rounded mb-2" style="max-height: 200px; opacity: 0.5;">
                                        <p class="mb-0 small text-muted">Klik kotak ini untuk upload cover</p>
                                    </div>

                                    <input type="file" name="gambar" id="fileInput" class="d-none" accept="image/*" onchange="previewImage(event)" required>
                                    
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('fileInput').click()">
                                            <i class="bi bi-upload me-1"></i> Pilih Gambar
                                        </button>
                                        <small class="text-muted text-start" style="font-size: 0.75rem;">
                                            * Format: JPG/PNG<br>
                                            * Maks: 2MB<br>
                                            * Disarankan rasio potret (3:4)
                                        </small>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="index.php" class="btn btn-light border">
                                        <i class="bi bi-arrow-left me-2"></i> Batal
                                    </a>
                                    <button type="submit" name="simpan" class="btn btn-primary px-5 fw-bold">
                                        <i class="bi bi-save me-2"></i> SIMPAN BUKU
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/script.js"></script>
    
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.opacity = "1";
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>