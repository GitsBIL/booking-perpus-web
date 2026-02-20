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
$tarif_denda = 1000; 

$query = mysqli_query($koneksi, "
    SELECT p.*, b.judul_buku, b.gambar 
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_user = '$id_user'
    ORDER BY p.id_peminjaman DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Peminjaman - Book Ing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .table thead th { background-color: var(--wood-dark); color: white; border: none; padding: 15px; font-weight: 500; font-size: 0.9rem; }
        .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .book-thumb { width: 45px; height: 65px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-kembali { background-color: var(--copper-accent); color: white; border: none; padding: 5px 15px; font-size: 0.85rem; transition: 0.3s; }
        .btn-kembali:hover { background-color: #bf4900; color: white; transform: translateY(-2px); }
        .btn-denda { background-color: #dc3545; color: white; border: none; padding: 5px 15px; font-size: 0.85rem; transition: 0.3s; animation: pulse 2s infinite; }
        .btn-denda:hover { background-color: #bb2d3b; color: white; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
        .row-telat { background-color: #fff5f5; }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include '../components/sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-dark border-0" id="menu-toggle"><i class="bi bi-list fs-4"></i></button>
                <h5 class="m-0 fw-bold" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">Riwayat Transaksi</h5>
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
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Tgl Kembali</th>
                                    <th>Info Denda</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($query) > 0){
                                    while($row = mysqli_fetch_assoc($query)){
                                        $status_badge = "bg-secondary";
                                        $label_status = ucfirst($row['status_peminjaman']);
                                        $is_telat = false;
                                        $hari_telat = 0;
                                        $estimasi_denda = 0;
                                        $row_class = "";

                                        if($row['status_peminjaman'] == 'dipinjam') { 
                                            $status_badge = "bg-warning text-dark"; 
                                            $tgl_sekarang = date('Y-m-d');
                                            $tgl_tempo = $row['tgl_jatuh_tempo'];

                                            if($tgl_sekarang > $tgl_tempo){
                                                $is_telat = true;
                                                $date1 = new DateTime($tgl_tempo);
                                                $date2 = new DateTime($tgl_sekarang);
                                                $diff = $date2->diff($date1);
                                                $hari_telat = $diff->days;
                                                $estimasi_denda = $hari_telat * $tarif_denda;
                                                $row_class = "row-telat border-start border-4 border-danger";
                                            }
                                        } elseif ($row['status_peminjaman'] == 'kembali') { 
                                            $status_badge = "bg-success"; $label_status = "Selesai";
                                        }
                                ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if(!empty($row['gambar'])) { ?>
                                                <img src="../../../uploads/<?php echo $row['gambar']; ?>" class="book-thumb" alt="Cover">
                                            <?php } else { ?>
                                                <div class="book-thumb bg-light d-flex align-items-center justify-content-center border"><i class="bi bi-book text-muted"></i></div>
                                            <?php } ?>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['judul_buku']; ?></div>
                                                <small class="text-muted d-block" style="font-size: 0.75rem;">ID: #<?php echo $row['kode_transaksi']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small"><?php echo isset($row['tgl_pinjam']) ? date('d M Y', strtotime($row['tgl_pinjam'])) : '-'; ?></td>
                                    <td class="small <?php echo $is_telat ? 'text-danger fw-bold' : 'text-muted'; ?>">
                                        <?php echo isset($row['tgl_jatuh_tempo']) ? date('d M Y', strtotime($row['tgl_jatuh_tempo'])) : '-'; ?>
                                        <?php if($is_telat) echo '<br><i class="bi bi-exclamation-triangle-fill"></i> Lewat ' . $hari_telat . ' Hari'; ?>
                                    </td>
                                    <td><span class="badge rounded-pill <?php echo $status_badge; ?> px-3 py-2"><?php echo $label_status; ?></span></td>
                                    <td class="text-muted small"><?php echo isset($row['tgl_kembali']) ? date('d M Y', strtotime($row['tgl_kembali'])) : '-'; ?></td>
                                    <td>
                                        <?php 
                                        if(isset($row['denda']) && $row['denda'] > 0){
                                            echo "<span class='text-danger fw-bold'>Terbayar: Rp " . number_format($row['denda'], 0, ',', '.') . "</span>";
                                        } else if ($is_telat) {
                                            echo "<span class='badge bg-danger'>Berjalan: Rp " . number_format($estimasi_denda, 0, ',', '.') . "</span>";
                                        } else { echo "-"; }
                                        ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($row['status_peminjaman'] == 'dipinjam') { ?>
                                            <button onclick="konfirmasiKembali('<?php echo $row['id_peminjaman']; ?>', <?php echo $is_telat ? $estimasi_denda : 0; ?>, <?php echo $hari_telat; ?>)" 
                                                class="btn <?php echo $is_telat ? 'btn-denda' : 'btn-kembali'; ?> rounded-pill shadow-sm">
                                                <?php echo $is_telat ? '<i class="bi bi-cash-coin me-1"></i> Bayar & Kembali' : 'Kembalikan'; ?>
                                            </button>
                                        <?php } else { ?>
                                            <i class="bi bi-check-circle-fill text-success fs-4" title="Selesai"></i>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } } else { echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Belum ada riwayat transaksi.</td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function konfirmasiKembali(id, denda, hari) {
        let titleText = 'Kembalikan Buku?';
        let htmlText = "Apakah Anda yakin ingin mengembalikan buku ini?";
        let iconType = 'question';
        let confirmColor = '#D35400';

        if (denda > 0) {
            titleText = 'PERINGATAN DENDA!';
            htmlText = `Anda terlambat <b>${hari} hari</b>.<br>Denda yang harus dibayar: <b style="color:red; font-size:1.2rem;">Rp ${new Intl.NumberFormat('id-ID').format(denda)}</b><br><br>Lanjutkan proses pengembalian?`;
            iconType = 'warning';
            confirmColor = '#dc3545';
        }

        Swal.fire({
            title: titleText,
            html: htmlText,
            icon: iconType,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `proses_kembali.php?id=${id}`;
            }
        })
    }
</script>
</body>
</html>