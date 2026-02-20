<?php
session_start();
// 1. Cek Login
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../../config/koneksi.php';
$nama_user = $_SESSION['nama'];

// 2. Cek ID Buku
if(!isset($_GET['id'])){
    header("location:../dashboard.php");
    exit();
}

$id_buku = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id_buku'");
$buku = mysqli_fetch_assoc($query);

// Jika buku tidak ditemukan
if(!$buku){
    echo "<script>alert('Buku tidak ditemukan!'); window.location='../dashboard.php';</script>";
    exit();
}

// 3. Ambil Data (Nama Kolom SESUAI DATABASE)
$judul_buku    = $buku['judul_buku'];    // BUKAN 'judul'
$penulis       = $buku['penulis'];
$penerbit      = $buku['penerbit'];
$tahun         = $buku['tahun_terbit'];
$stok_tersedia = $buku['stok_tersedia']; // BUKAN 'stok'
$gambar        = $buku['gambar'];
$kategori      = isset($buku['kategori']) ? $buku['kategori'] : 'Umum';

// Cek Ketersediaan
$is_available = ($stok_tersedia > 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Buku - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root { --wood-dark: #3E2723; --copper-accent: #D35400; --bg-cream: #FDFBF7; }
        body { background-color: var(--bg-cream); font-family: 'Poppins', sans-serif; }
        
        #wrapper { display: flex; width: 100%; min-height: 100vh; }
        #sidebar-wrapper { min-width: 260px; max-width: 260px; background-color: var(--wood-dark); color: #fff; }
        
        .sidebar-brand { padding: 30px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .brand-icon { width: 40px; height: auto; }
        .brand-text { font-size: 24px; font-weight: 700; margin: 0; }
        .brand-text span { color: var(--copper-accent); }
        
        .list-group-item { background: transparent; border: none; color: rgba(255,255,255,0.7); padding: 15px 25px; display: flex; align-items: center; font-weight: 500; transition: 0.3s; }
        .list-group-item:hover, .list-group-item.active { background-color: #5D4037; color: var(--copper-accent); border-left: 4px solid var(--copper-accent); }
        .list-group-item i { margin-right: 15px; font-size: 1.2rem; width: 25px; text-align: center; }

        .card-detail { border: none; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
        .cover-wrapper { background: #f8f9fa; padding: 30px; display: flex; align-items: center; justify-content: center; height: 100%; }
        .book-big-cover { max-width: 100%; max-height: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border-radius: 10px; }
        
        .badge-category { background: var(--wood-dark); color: white; padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; letter-spacing: 1px; }
        .info-label { color: #6c757d; font-size: 0.9rem; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: #212529; font-size: 1rem; }
        
        .btn-pinjam { background: var(--copper-accent); border: none; color: white; padding: 12px 30px; border-radius: 10px; font-weight: 600; transition: 0.3s; width: 100%; }
        .btn-pinjam:hover { background: #b84a00; transform: translateY(-2px); color: white; }
        .btn-pinjam:disabled { background: #ccc; cursor: not-allowed; transform: none; }
        
        .btn-wishlist { border: 2px solid #eee; color: #666; padding: 12px; border-radius: 10px; transition: 0.3s; width: 100%; display: block; text-align: center; }
        .btn-wishlist:hover { border-color: #ff4757; color: #ff4757; background: #fff5f5; }
    </style>
</head>
<body>

<div id="wrapper">
    <div id="sidebar-wrapper">
        <div class="sidebar-brand">
            <img src="../../../assets/img/Icon_kecil.png" alt="Logo" class="brand-icon">
            <h1 class="brand-text">Book<span>Ing.</span></h1>
        </div>
        <div class="list-group list-group-flush mt-3">
            <div class="small fw-bold px-4 mb-2 text-white-50">MENU UTAMA</div>
            <a href="../dashboard.php" class="list-group-item active"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a href="../katalog.php" class="list-group-item"><i class="bi bi-compass"></i> Jelajah Buku</a>
            <a href="../wishlist/index.php" class="list-group-item"><i class="bi bi-heart"></i> Koleksi Favorit</a>
            <div class="small fw-bold px-4 mb-2 mt-4 text-white-50">AKTIVITAS</div>
            <a href="../transaksi/riwayat.php" class="list-group-item"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a href="../profil/index.php" class="list-group-item"><i class="bi bi-person-circle"></i> Profil Saya</a>
            <a href="../../auth/logout.php" class="list-group-item text-danger mt-4 pt-4 border-top border-secondary"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
    </div>

    <div style="width: 100%;">
        <nav class="navbar navbar-light bg-white px-4 py-3 shadow-sm justify-content-between">
            <h5 class="m-0 fw-bold text-secondary">Detail Buku</h5>
            <div class="dropdown">
                <a href="#" class="text-decoration-none text-dark fw-bold dropdown-toggle" data-bs-toggle="dropdown">
                    Halo, <?php echo explode(' ', $nama_user)[0]; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                    <li><a class="dropdown-item" href="../../auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <a href="javascript:history.back()" class="text-decoration-none text-secondary mb-3 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            <div class="card card-detail">
                <div class="row g-0">
                    <div class="col-md-4 col-lg-3">
                        <div class="cover-wrapper">
                            <?php if(!empty($gambar)) { ?>
                                <img src="../../../uploads/<?php echo $gambar; ?>" class="book-big-cover" alt="Cover Buku">
                            <?php } else { ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 300px; border-radius: 10px;">
                                    <i class="bi bi-book fs-1 text-muted opacity-50"></i>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-9">
                        <div class="card-body p-4 p-lg-5">
                            <div class="mb-2">
                                <span class="badge-category text-uppercase">Pustaka</span>
                            </div>
                            
                            <h2 class="fw-bold mb-1" style="color: var(--wood-dark);"><?php echo $judul_buku; ?></h2>
                            <p class="text-muted fs-5 mb-4">Penulis: <?php echo $penulis; ?></p>

                            <div class="row g-3 mb-4 border-top border-bottom py-3">
                                <div class="col-6 col-md-3">
                                    <div class="info-label">Penerbit</div>
                                    <div class="info-value"><?php echo $penerbit; ?></div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-label">Tahun Terbit</div>
                                    <div class="info-value"><?php echo $tahun; ?></div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="info-label">Stok Tersedia</div>
                                    <?php if($is_available) { ?>
                                        <div class="info-value text-success">
                                            <i class="bi bi-check-circle-fill me-1"></i> <?php echo $stok_tersedia; ?> Eks.
                                        </div>
                                    <?php } else { ?>
                                        <div class="info-value text-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i> Habis
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">Tentang Buku Ini:</h6>
                                <p class="text-secondary" style="line-height: 1.6;">
                                    Buku <strong>"<?php echo $judul_buku; ?>"</strong> diterbitkan oleh <?php echo $penerbit; ?>. 
                                    Buku ini tersedia di perpustakaan kami dan dapat dipinjam oleh anggota aktif.
                                </p>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-5">
                                    <form action="../transaksi/proses_pinjam.php" method="POST">
                                        <input type="hidden" name="id_buku" value="<?php echo $id_buku; ?>">
                                        <?php if($is_available) { ?>
                                            <button type="submit" class="btn btn-pinjam">
                                                <i class="bi bi-journal-plus me-2"></i> Pinjam Sekarang
                                            </button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-pinjam" disabled>
                                                <i class="bi bi-lock me-2"></i> Stok Habis
                                            </button>
                                        <?php } ?>
                                    </form>
                                </div>
                                <div class="col-md-6 col-lg-5">
                                    <form action="../wishlist/proses_wishlist.php" method="POST">
                                        <input type="hidden" name="id_buku" value="<?php echo $id_buku; ?>">
                                        <button type="submit" class="btn btn-wishlist">
                                            <i class="bi bi-heart me-2"></i> Simpan ke Favorit
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>