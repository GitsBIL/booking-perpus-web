<?php
session_start();
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../auth/login.php?pesan=belum_login");
    exit();
}
include '../../config/koneksi.php';
$nama_user = $_SESSION['nama'];
$nama_depan = explode(' ', $nama_user)[0];

$query_kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$where = "WHERE 1=1"; 
$order = "ORDER BY id_buku DESC";

if(isset($_GET['kata_kunci']) && !empty($_GET['kata_kunci'])){
    $key = $_GET['kata_kunci'];
    $where .= " AND (judul_buku LIKE '%$key%' OR penulis LIKE '%$key%')";
}
if(isset($_GET['kategori']) && !empty($_GET['kategori'])){
    $id_kat = $_GET['kategori'];
    $where .= " AND id_kategori = '$id_kat'";
}
if(isset($_GET['sort'])){
    if($_GET['sort'] == 'asc') { $order = "ORDER BY judul_buku ASC"; }
    elseif($_GET['sort'] == 'desc') { $order = "ORDER BY judul_buku DESC"; }
}
$query_buku = mysqli_query($koneksi, "SELECT * FROM buku $where $order");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog Pustaka - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .filter-container { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); }
        .form-control, .form-select { border: 1px solid #E0D8CF; padding: 10px 15px; border-radius: 8px; }
        .form-control:focus, .form-select:focus { border-color: var(--copper-accent); box-shadow: 0 0 0 3px rgba(211, 84, 0, 0.1); }
        .book-card { background: #fff; border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: all 0.3s; height: 100%; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(62, 39, 35, 0.1); }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include 'components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-dark border-0" id="menu-toggle"><i class="bi bi-list fs-4"></i></button>
                <h5 class="m-0 fw-bold" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">Jelajah Pustaka</h5>
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
            <div class="filter-container mb-4">
                <form action="" method="GET">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="small text-muted mb-1 fw-bold">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="kata_kunci" class="form-control border-start-0" placeholder="Judul buku, penulis..." value="<?php echo isset($_GET['kata_kunci']) ? $_GET['kata_kunci'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted mb-1 fw-bold">Kategori</label>
                            <select name="kategori" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php while($kat = mysqli_fetch_array($query_kategori)) { ?>
                                    <option value="<?php echo $kat['id_kategori']; ?>" <?php if(isset($_GET['kategori']) && $_GET['kategori'] == $kat['id_kategori']) echo 'selected'; ?>>
                                        <?php echo $kat['nama_kategori']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-muted mb-1 fw-bold">Urutan</label>
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="">Terbaru</option>
                                <option value="asc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?>>Judul (A-Z)</option>
                                <option value="desc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?>>Judul (Z-A)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn text-white w-100 fw-bold" style="background-color: var(--wood-dark);">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
                <?php 
                if(mysqli_num_rows($query_buku) > 0){
                    while($buku = mysqli_fetch_array($query_buku)){ 
                        $stok = $buku['stok_tersedia'];
                ?>
                <div class="col">
                    <div class="book-card d-flex flex-column">
                        <div class="position-relative">
                            <div style="width:100%; padding-top:140%; position:relative; overflow:hidden; border-radius: 12px 12px 0 0;">
                                <?php if(!empty($buku['gambar'])) { ?>
                                    <img src="../../uploads/<?php echo $buku['gambar']; ?>" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                                <?php } else { ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center position-absolute w-100 h-100" style="top:0; left:0;"><i class="bi bi-book fs-1 text-muted opacity-25"></i></div>
                                <?php } ?>
                            </div>
                            <?php if($stok <= 0) { ?>
                                <span class="position-absolute top-0 end-0 m-2 badge bg-danger shadow-sm">Habis</span>
                            <?php } ?>
                        </div>
                        
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h6 class="fw-bold text-dark text-truncate mb-1" title="<?php echo $buku['judul_buku']; ?>"><?php echo $buku['judul_buku']; ?></h6>
                            <small class="text-secondary d-block mb-3 text-truncate"><?php echo $buku['penulis']; ?></small>
                            <div class="mt-auto d-flex gap-2">
                                <a href="buku/detail.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-outline-dark btn-sm rounded-pill flex-fill">Detail</a>
                                <?php if($stok > 0) { ?>
                                    <form action="transaksi/proses_pinjam.php" method="POST" class="flex-fill" id="formPinjam<?php echo $buku['id_buku']; ?>">
                                        <input type="hidden" name="id_buku" value="<?php echo $buku['id_buku']; ?>">
                                        <button type="button" class="btn btn-sm text-white rounded-pill w-100" style="background-color: var(--copper-accent);" onclick="konfirmasiPinjam('<?php echo $buku['judul_buku']; ?>', 'formPinjam<?php echo $buku['id_buku']; ?>')">Pinjam</button>
                                    </form>
                                <?php } else { ?>
                                    <button class="btn btn-secondary btn-sm rounded-pill flex-fill" disabled>Habis</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } } else { echo "<div class='col-12 py-5 text-center'><h4 class='text-muted mt-3'>Buku tidak ditemukan</h4></div>"; } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function konfirmasiPinjam(judul, formId) {
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