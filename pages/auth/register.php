<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Anggota Baru - PerpusDigital</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --wood-dark: #3E2723;
            --copper-accent: #A16E4B;
            --bg-cream: #F5F1E8;
        }
        body {
            background-color: var(--bg-cream);
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(62, 39, 35, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .card-header-custom {
            background: var(--wood-dark);
            padding: 30px 20px;
            text-align: center;
            color: #fff;
        }
        .btn-register {
            background: var(--copper-accent);
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 8px;
            transition: 0.3s;
            color: #fff;
        }
        .btn-register:hover {
            background: var(--wood-dark);
            transform: translateY(-2px);
        }
        .form-control {
            background: #f8f9fa;
            border: 1px solid #eee;
            padding: 12px;
        }
        .form-control:focus {
            border-color: var(--copper-accent);
            box-shadow: none;
            background: #fff;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="card-header-custom">
            <h4 class="fw-bold mb-0">Buat Akun Baru</h4>
            <small class="opacity-75">Bergabunglah dengan perpustakaan digital kami</small>
        </div>
        <div class="p-4">
            <form action="proses_register.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="nama" class="form-control border-start-0" placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nomor Identitas (NIS/NIM)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-muted"></i></span>
                        <input type="number" name="nomor_identitas" class="form-control border-start-0" placeholder="Contoh: 12345678" required>
                    </div>
                    <div class="form-text text-muted" style="font-size: 0.75rem;">Gunakan NIS/NIM sebagai username login.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="Buat password aman" required>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-register mb-3">Daftar Sekarang</button>
                
                <div class="text-center">
                    <small class="text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold" style="color: var(--copper-accent);">Login disini</a></small>
                </div>

            </form>
        </div>
    </div>

</body>
</html>