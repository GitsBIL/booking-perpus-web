<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data Anggota</h5>
                </div>
                <div class="card-body">
                    <form action="proses_edit.php" method="POST">
                        <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nomor Identitas</label>
                            <input type="text" name="nomor_identitas" class="form-control" value="<?= $data['nomor_identitas']; ?>" readonly>
                            <small class="text-muted">NIP/NIM tidak bisa diubah.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Akun</label>
                            <select name="status" class="form-select">
                                <option value="aktif" <?= ($data['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif (Bisa Login)</option>
                                <option value="nonaktif" <?= ($data['status'] == 'nonaktif') ? 'selected' : ''; ?>>Non-Aktif (Dibekukan)</option>
                            </select>
                        </div>

                        <div class="mb-3 bg-white p-3 border rounded">
                            <label class="form-label fw-bold">Ganti Password (Opsional)</label>
                            <input type="text" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                            <small class="text-danger">Hati-hati! Isi ini hanya jika anggota lupa password.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="update" class="btn btn-warning">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>