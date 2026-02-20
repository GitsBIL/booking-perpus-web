<?php
session_start();
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../../config/koneksi.php';
$id_user = $_SESSION['id_user'];

// Ambil data user
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id_user'");
$data = mysqli_fetch_assoc($query);
$nama_depan = explode(' ', $data['nama_lengkap'])[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengaturan Akun - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    
    <style>
        .card-profile { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
        .avatar-big { 
            width: 100px; height: 100px; 
            background: var(--copper-accent); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 40px; font-weight: bold; color: white; 
            margin: 0 auto 15px; 
            border: 4px solid rgba(255,255,255,0.2); 
            box-shadow: 0 5px 15px rgba(211, 84, 0, 0.2);
        }
        .form-control { border-radius: 8px; padding: 12px 15px; border: 1px solid #E0D8CF; }
        .form-control:focus { border-color: var(--copper-accent); box-shadow: 0 0 0 3px rgba(211, 84, 0, 0.1); }
        .btn-save { background-color: var(--wood-dark); color: white; border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-save:hover { background-color: var(--copper-accent); transform: translateY(-2px); }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include '../components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-dark border-0" id="menu-toggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="m-0 fw-bold" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">
                    Pengaturan Akun
                </h5>
            </div>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:35px; height:35px; border: 1px solid var(--copper-accent);">
                        <i class="bi bi-person-fill" style="color: var(--wood-dark);"></i>
                    </div>
                    <span class="fw-bold small me-1"><?php echo $nama_depan; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                    <li><a class="dropdown-item active" href="#">Edit Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../auth/logout.php">Keluar</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <form action="proses_profil.php" method="POST">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card-profile h-100">
                            <div class="card-body text-center p-5">
                                <div class="avatar-big">
                                    <?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?>
                                </div>
                                <h4 class="fw-bold mb-1" style="font-family: 'Merriweather', serif;"><?php echo $data['nama_lengkap']; ?></h4>
                                <span class="badge bg-light text-dark border mb-4 px-3 py-2">Anggota Perpustakaan</span>
                                
                                <div class="p-3 bg-light rounded text-start mb-3 border-start border-4 border-warning">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">NOMOR IDENTITAS (NIS)</small>
                                    <div class="fw-bold text-dark font-monospace"><?php echo $data['nomor_identitas']; ?></div>
                                </div>
                                <div class="p-3 bg-light rounded text-start border-start border-4 border-success">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">STATUS AKUN</small>
                                    <div class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card-profile h-100">
                            <div class="card-body p-4 p-md-5">
                                <h5 class="fw-bold mb-4 pb-2 border-bottom" style="color: var(--wood-dark);">Perbarui Informasi</h5>
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted fw-bold small">NAMA LENGKAP</label>
                                    <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $data['nama_lengkap']; ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted fw-bold small">USERNAME / NIS</label>
                                    <input type="text" class="form-control bg-light text-muted" value="<?php echo $data['nomor_identitas']; ?>" readonly>
                                    <small class="text-muted fst-italic" style="font-size: 0.75rem;">*Hubungi admin jika ingin mengubah NIS.</small>
                                </div>

                                <div class="alert alert-light border mt-5 mb-4">
                                    <h6 class="fw-bold text-dark"><i class="bi bi-shield-lock me-2"></i>Keamanan</h6>
                                    <div class="mb-2 mt-3">
                                        <label class="form-label text-muted fw-bold small">PASSWORD BARU</label>
                                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-save px-4 py-2">
                                        <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
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
</body>
</html>