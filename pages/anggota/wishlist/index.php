<?php
session_start();
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

include '../../../config/koneksi.php';
$id_user = $_SESSION['id_user'];
$nama_user = $_SESSION['nama'];
$nama_depan = explode(' ', $nama_user)[0];

// Query Wishlist
$query = mysqli_query($koneksi, "
    SELECT w.*, b.judul_buku, b.penulis, b.gambar, b.id_buku, b.stok_tersedia 
    FROM wishlist w
    JOIN buku b ON w.id_buku = b.id_buku
    WHERE w.id_user = '$id_user'
    ORDER BY w.id_wishlist DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Koleksi Favorit - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    
    <style>
        .book-card {
            background: #fff; border: none; border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: all 0.3s; height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px); box-shadow: 0 15px 30px rgba(62, 39, 35, 0.1);
        }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon { font-size: 5rem; color: #e0e0e0; margin-bottom: 20px; }
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
                    Koleksi Favorit
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
                    <li><a class="dropdown-item" href="../profil/index.php">Edit Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../../auth/logout.php">Keluar</a></li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--wood-dark);">Pustaka Pribadi</h4>
                    <p class="text-muted small m-0">Buku yang kamu simpan untuk dibaca nanti.</p>
                </div>
                <span class="badge bg-secondary rounded-pill px-3 py-2"><?php echo mysqli_num_rows($query); ?> Item</span>
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
                <?php 
                if(mysqli_num_rows($query) > 0){
                    while($row = mysqli_fetch_array($query)){ 
                        $stok = $row['stok_tersedia'];
                ?>
                <div class="col">
                    <div class="book-card d-flex flex-column">
                        <div class="position-relative">
                            <div style="width:100%; padding-top:140%; position:relative; overflow:hidden; border-radius: 12px 12px 0 0;">
                                <?php if(!empty($row['gambar'])) { ?>
                                    <img src="../../../uploads/<?php echo $row['gambar']; ?>" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                                <?php } else { ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center position-absolute w-100 h-100" style="top:0; left:0;">
                                        <i class="bi bi-book fs-1 text-muted opacity-25"></i>
                                    </div>
                                <?php } ?>
                            </div>
                            <a href="proses_hapus.php?id=<?php echo $row['id_wishlist']; ?>" class="position-absolute top-0 end-0 m-2 btn btn-danger btn-sm rounded-circle shadow-sm" onclick="return confirm('Hapus dari koleksi?')" title="Hapus" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>
                        
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h6 class="fw-bold text-dark text-truncate mb-1" title="<?php echo $row['judul_buku']; ?>">
                                <?php echo $row['judul_buku']; ?>
                            </h6>
                            <small class="text-secondary d-block mb-3 text-truncate"><?php echo $row['penulis']; ?></small>
                            
                            <div class="mt-auto d-flex gap-2">
                                <a href="../buku/detail.php?id=<?php echo $row['id_buku']; ?>" class="btn btn-outline-dark btn-sm rounded-pill flex-fill">Detail</a>
                                <?php if($stok > 0) { ?>
                                    <form action="../transaksi/proses_pinjam.php" method="POST" class="flex-fill">
                                        <input type="hidden" name="id_buku" value="<?php echo $row['id_buku']; ?>">
                                        <button type="submit" class="btn btn-sm text-white rounded-pill w-100" style="background-color: var(--copper-accent);" onclick="return confirm('Pinjam buku ini?')">Pinjam</button>
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
                    echo '
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-bookmarks empty-icon"></i>
                            <h5 class="fw-bold text-secondary">Koleksi masih kosong</h5>
                            <p class="text-muted mb-4">Jelajahi katalog dan simpan buku favoritmu di sini.</p>
                            <a href="../katalog.php" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: var(--wood-dark);">Mulai Jelajah</a>
                        </div>
                    </div>';
                }
                ?>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/script.js"></script>
</body>
</html>