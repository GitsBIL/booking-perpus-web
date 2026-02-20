<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

$id_peminjaman = $_GET['id'];
$query = mysqli_query($koneksi, "
    SELECT p.*, u.nama_lengkap, b.judul_buku 
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_peminjaman = '$id_peminjaman'
");
$data = mysqli_fetch_assoc($query);

// --- LOGIKA HITUNG DENDA ---
$tgl_jatuh_tempo = $data['tgl_jatuh_tempo'];
$tgl_kembali_aktual = date('Y-m-d'); // Anggap dikembalikan hari ini

// Hitung selisih hari
$selisih = strtotime($tgl_kembali_aktual) - strtotime($tgl_jatuh_tempo);
$jarak_hari = floor($selisih / (60 * 60 * 24)); // Konversi detik ke hari

if ($jarak_hari > 0) {
    $terlambat = $jarak_hari;
    $denda = $terlambat * 1000; // Denda Rp 1.000 per hari
} else {
    $terlambat = 0;
    $denda = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Konfirmasi Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🔄 Proses Pengembalian Buku</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses_pengembalian.php" method="POST">
                        <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                        <input type="hidden" name="id_buku" value="<?= $data['id_buku']; ?>">
                        
                        <div class="mb-3">
                            <label class="text-muted small">Nama Peminjam</label>
                            <h5><?= $data['nama_lengkap']; ?></h5>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Judul Buku</label>
                            <h5><?= $data['judul_buku']; ?></h5>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="text-muted small">Tgl Jatuh Tempo</label>
                                <p class="fw-bold text-danger"><?= $data['tgl_jatuh_tempo']; ?></p>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small">Tgl Kembali (Hari Ini)</label>
                                <p class="fw-bold text-success"><?= $tgl_kembali_aktual; ?></p>
                            </div>
                        </div>

                        <div class="alert <?= ($terlambat > 0) ? 'alert-danger' : 'alert-success'; ?>">
                            <div class="d-flex justify-content-between">
                                <span>Keterlambatan:</span>
                                <strong><?= $terlambat; ?> Hari</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Total Denda:</span>
                                <strong class="fs-5">Rp <?= number_format($denda, 0, ',', '.'); ?></strong>
                            </div>
                        </div>

                        <input type="hidden" name="tgl_kembali" value="<?= $tgl_kembali_aktual; ?>">
                        <input type="hidden" name="denda" value="<?= $denda; ?>">

                        <div class="d-grid gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary btn-lg">
                                ✅ Konfirmasi Pengembalian
                            </button>
                            <a href="index.php" class="btn btn-light text-muted">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>