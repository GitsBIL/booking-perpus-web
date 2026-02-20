<?php
session_start();

// 1. Cek Login
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../auth/login.php?pesan=belum_login");
    exit();
}

include '../../config/koneksi.php';

$id_user = $_SESSION['id_user'];
$nama_user = $_SESSION['nama'];
$nama_depan = explode(' ', $nama_user)[0];

// LOGIKA REKOMENDASI (Top 4)
$query_buku = mysqli_query($koneksi, "
    SELECT b.*, COUNT(p.id_buku) as popularitas 
    FROM buku b 
    LEFT JOIN peminjaman p ON b.id_buku = p.id_buku 
    GROUP BY b.id_buku 
    ORDER BY popularitas DESC, RAND() 
    LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Beranda Pustaka - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, rgba(62, 39, 35, 1) 0%, rgba(161, 110, 75, 1) 100%);
            border-radius: 20px;
            padding: 50px 40px;
            color: #ffffff !important; 
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(62, 39, 35, 0.2);
        }
        
        .hero-text-contrast {
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .hero-pattern {
            position: absolute; top: 0; right: 0; bottom: 0; left: 0;
            opacity: 0.1;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .book-card {
            border: none; background: #fff; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease;
        }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(62, 39, 35, 0.15); }
        
        .btn-cari {
            background-color: var(--copper-accent); border: none; padding: 12px 30px; font-weight: 600; transition: 0.3s;
        }
        .btn-cari:hover { background-color: #E67E22; transform: scale(1.05); }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include 'components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-dark border-0" id="menu-toggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="m-0 fw-bold" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">
                    Beranda Pustaka
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
                    <li><a class="dropdown-item" href="profil/index.php">Edit Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../auth/logout.php">Keluar</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="hero-section mb-5">
                <div class="hero-pattern"></div>
                <div class="row align-items-center position-relative z-1">
                    <div class="col-lg-8">
                        <h1 class="display-5 fw-bold mb-3 text-white hero-text-contrast" style="font-family: 'Merriweather', serif;">
                            Selamat Datang, <?php echo $nama_depan; ?>.
                        </h1>
                        <p class="lead mb-4 opacity-75 text-white hero-text-contrast">
                            "Buku adalah jendela dunia. Mari jelajahi pengetahuan baru hari ini."
                        </p>
                        <form action="katalog.php" method="GET" class="d-flex gap-2 bg-white p-2 rounded-pill shadow-sm" style="max-width: 500px;">
                            <input type="text" name="kata_kunci" class="form-control border-0 rounded-pill px-3" placeholder="Cari judul buku, penulis..." style="box-shadow: none;">
                            <button type="submit" class="btn btn-cari text-white rounded-pill px-4">Cari</button>
                        </form>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block text-center">
                        <i class="bi bi-book-half" style="font-size: 8rem; color: rgba(255,255,255,0.2);"></i>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-end mb-3">
                <h4 class="fw-bold m-0" style="color: var(--wood-dark); font-family: 'Merriweather', serif;">Rekomendasi Terhangat</h4>
                <a href="katalog.php" class="text-decoration-none fw-bold small" style="color: var(--copper-accent);">
                    Lihat Koleksi Lengkap <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-4 g-4">
                <?php 
                if(mysqli_num_rows($query_buku) > 0){
                    while($buku = mysqli_fetch_array($query_buku)){ 
                        $stok = $buku['stok_tersedia'];
                ?>
                <div class="col">
                    <div class="book-card h-100 d-flex flex-column">
                        <div class="position-relative">
                            <div style="width:100%; padding-top:140%; position:relative; overflow:hidden; border-radius: 12px 12px 0 0;">
                                <?php if(!empty($buku['gambar'])) { ?>
                                    <img src="../../uploads/<?php echo $buku['gambar']; ?>" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                                <?php } else { ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center position-absolute w-100 h-100" style="top:0; left:0;">
                                        <i class="bi bi-book fs-1 text-muted opacity-25"></i>
                                    </div>
                                <?php } ?>
                            </div>
                            <span class="position-absolute top-0 end-0 m-2 badge <?php echo ($stok > 0) ? 'bg-success' : 'bg-danger'; ?> shadow-sm">
                                <?php echo ($stok > 0) ? "$stok Tersedia" : "Habis"; ?>
                            </span>
                        </div>
                        
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h6 class="fw-bold text-dark text-truncate mb-1" title="<?php echo $buku['judul_buku']; ?>">
                                <?php echo $buku['judul_buku']; ?>
                            </h6>
                            <small class="text-muted d-block mb-3 text-truncate"><?php echo $buku['penulis']; ?></small>
                            
                            <div class="mt-auto d-flex gap-2">
                                <a href="buku/detail.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-outline-dark btn-sm rounded-pill flex-fill">Detail</a>
                                
                                <?php if($stok > 0) { ?>
                                    <form action="transaksi/proses_pinjam.php" method="POST" class="flex-fill" id="formPinjamDashboard<?php echo $buku['id_buku']; ?>">
                                        <input type="hidden" name="id_buku" value="<?php echo $buku['id_buku']; ?>">
                                        <button type="button" class="btn btn-sm text-white rounded-pill w-100" style="background-color: var(--copper-accent);" 
                                            onclick="konfirmasiPinjamDashboard('<?php echo $buku['judul_buku']; ?>', 'formPinjamDashboard<?php echo $buku['id_buku']; ?>')">
                                            Pinjam
                                        </button>
                                    </form>
                                <?php } else { ?>
                                    <button class="btn btn-secondary btn-sm rounded-pill flex-fill" disabled>Habis</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else {
                    echo "<div class='col-12 py-5 text-center text-muted'>Belum ada rekomendasi.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiPinjamDashboard(judul, formId) {
        Swal.fire({
            title: 'Pinjam Buku?',
            text: "Anda akan meminjam buku: " + judul,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#D35400',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pinjam',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
</script>

</body>
</html>