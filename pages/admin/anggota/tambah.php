<?php
session_start();

// Cek Security
if($_SESSION['status'] != "login" || $_SESSION['role'] != "admin"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Anggota - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body>

<div id="wrapper">
    <?php include '../components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <?php include '../components/navbar.php'; ?>

        <div class="container-fluid p-4">
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="fw-bold mb-0" style="color: var(--wood-dark); font-family: 'Merriweather', serif;">
                                <i class="bi bi-person-plus-fill me-2"></i> Registrasi Anggota Baru
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="proses_tambah.php" method="POST">
                                
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Nomor Identitas (NIP/NIM)</label>
                                    <input type="text" name="nomor_identitas" class="form-control" placeholder="Contoh: 2024001" required style="border-radius: 8px; padding: 10px;">
                                    <div class="form-text">Digunakan sebagai Username saat login.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap Anggota" required style="border-radius: 8px; padding: 10px;">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small text-muted fw-bold">Password Awal</label>
                                    <input type="text" name="password" class="form-control" placeholder="Buat password default" required style="border-radius: 8px; padding: 10px;">
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-2">
                                    <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                                    <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: var(--copper-accent);">
                                        <i class="bi bi-save me-1"></i> Simpan Data
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