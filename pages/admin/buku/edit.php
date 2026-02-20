<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

// 1. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// 2. Ambil Data Buku Lama
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id'");
$data = mysqli_fetch_assoc($query);

// Cek jika data tidak ada (misal ID ditembak sembarangan)
if (!$data) {
    echo "<script>alert('Data buku tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Data Buku</title>
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
            position: relative;
        }
        .upload-area:hover {
            border-color: var(--copper-accent);
            background: #fff;
        }
        .current-tag {
            position: absolute; top: 10px; right: 10px;
            background: rgba(0,0,0,0.6); color: #fff;
            padding: 2px 8px; border-radius: 4px; font-size: 0.7rem;
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
                                <li class="breadcrumb-item active text-dark" aria-current="page">Edit Buku</li>
                            </ol>
                        </nav>
                        <h4 class="fw-bold mb-0" style="color: var(--wood-dark);">✏️ Perbarui Data Buku</h4>
                    </div>
                </div>

                <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_buku" value="<?= $data['id_buku']; ?>">
                    <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>">

                    <div class="row g-4">
                        
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Informasi Pustaka</h6>
                                </div>
                                <div class="card-body p-4">
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">JUDUL BUKU</label>
                                        <input type="text" name="judul_buku" class="form-control form-control-lg fw-bold text-dark" value="<?= $data['judul_buku']; ?>" required>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">KATEGORI</label>
                                            <select name="id_kategori" class="form-select" required>
                                                <option value="" disabled>Pilih Kategori...</option>
                                                <?php
                                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                                while ($k = mysqli_fetch_assoc($kat)) {
                                                    // Logic: Jika ID kategori sama dengan data buku, tambahkan 'selected'
                                                    $selected = ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                                                    echo "<option value='{$k['id_kategori']}' $selected>{$k['nama_kategori']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">PENULIS</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                                <input type="text" name="penulis" class="form-control" value="<?= $data['penulis']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">PENERBIT</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                                <input type="text" name="penerbit" class="form-control" value="<?= $data['penerbit']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">TAHUN TERBIT</label>
                                            <input type="number" name="tahun_terbit" class="form-control" value="<?= $data['tahun_terbit']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">STOK TERSEDIA</label>
                                        <div class="input-group w-50">
                                            <span class="input-group-text bg-light">Qty</span>
                                            <input type="number" name="stok_tersedia" class="form-control fw-bold" value="<?= $data['stok_tersedia']; ?>" min="0" required>
                                        </div>
                                        <small class="text-muted">Ubah angka ini jika ada penambahan atau pengurangan fisik buku.</small>
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
                                        <span class="current-tag">Cover Saat Ini</span>
                                        <img id="preview" src="../../../uploads/<?= $data['gambar']; ?>" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 250px;">
                                        <p class="mb-0 small text-primary fw-bold mt-2">Klik untuk ganti gambar</p>
                                    </div>

                                    <input type="file" name="gambar" id="fileInput" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    
                                    <div class="alert alert-light border small text-start">
                                        <i class="bi bi-info-circle me-1"></i> <strong>Catatan:</strong><br>
                                        Biarkan kosong jika tidak ingin mengubah cover buku.
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="index.php" class="btn btn-light border px-4">
                                        <i class="bi bi-arrow-left me-2"></i> Kembali
                                    </a>
                                    <button type="submit" name="update" class="btn btn-warning px-5 fw-bold text-dark">
                                        <i class="bi bi-check-circle-fill me-2"></i> UPDATE PERUBAHAN
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
                // Ganti label 'Cover Saat Ini' jadi 'Preview Baru' (Opsional, visual only)
                document.querySelector('.current-tag').textContent = 'Preview Baru';
                document.querySelector('.current-tag').style.background = '#28a745';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>