<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Book Ing App</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            background-color: #fff;
        }

        /* --- LAYOUT KHUSUS LOGIN --- */
        .login-container {
            height: 100vh;
        }

        /* SISI KIRI (GAMBAR) */
        .left-side {
            position: relative;
            background: url('https://images.unsplash.com/photo-1507842217121-9e87bd229f27?q=80&w=2574&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            color: white;
        }

        .left-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(62, 39, 35, 0.95) 0%, rgba(161, 110, 75, 0.85) 100%);
            z-index: 1;
        }

        .left-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-logo-top {
            width: 100px;
            margin-bottom: 25px;
            filter: brightness(0) invert(1);
        }

        .hero-text h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 15px;
        }
        
        .tagline {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .description {
            font-size: 1rem;
            opacity: 0.7;
            max-width: 500px;
            line-height: 1.6;
        }

        /* SISI KANAN (FORM) */
        .right-side {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }

        .logo-center {
            width: 120px;
            margin-bottom: 20px;
        }

        /* Custom Input */
        .custom-input {
            background-color: #F0F2F5;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: 0.3s;
        }
        
        .custom-input:focus {
            background-color: #fff;
            border-color: var(--wood-dark);
            box-shadow: none;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .btn-masuk {
            background-color: var(--wood-dark);
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            border: none;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-masuk:hover {
            background-color: #2c1b18;
            color: #fff;
        }

        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute; top: 50%; right: 15px; transform: translateY(-50%);
            cursor: pointer; color: var(--text-muted);
        }
        .link-forgot {
            color: var(--copper-accent);
            text-decoration: none; font-weight: 600; font-size: 0.9rem;
        }
        .link-forgot:hover { text-decoration: underline; }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .left-side { display: none !important; }
            .right-side { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="container-fluid g-0">
    <div class="row g-0 login-container">
        
        <div class="col-lg-7 d-none d-lg-flex left-side">
            <div class="left-overlay"></div>
            <div class="left-content">
                <div class="brand-top">
                    <img src="../../assets/img/Icon_Book_Ing.png" alt="Book Ing Logo" class="brand-logo-top">
                </div>
                <div class="hero-text">
                    <h1>Book <span style="color: #FFB74D;">Ing.</span></h1>
                    <div class="tagline">Don't just look. Book it.</div>
                    <p class="description">
                        Sistem reservasi perpustakaan modern. Cepat, mudah, dan efisien. 
                        Nikmati pengalaman membaca tanpa batas waktu dan ruang.
                    </p>
                </div>
                <div class="copyright small opacity-50">&copy; 2026 Book Ing Corp.</div>
            </div>
        </div>

        <div class="col-lg-5 right-side">
            <div class="login-form-wrapper text-center">
                
                <img src="../../assets/img/Icon_Book_Ing.png" alt="Logo" class="logo-center">
                
                <h3 class="fw-bold" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">
                    Book<span style="color: var(--copper-accent);">Ing.</span>
                </h3>
                <h4 class="fw-bold mb-2 text-dark">Halo, Sobat Pustaka!</h4>
                <p class="text-muted small mb-4">Silakan login untuk mulai mencari buku.</p>

                <?php 
                if(isset($_GET['pesan'])){
                    if($_GET['pesan'] == "gagal"){
                        echo "<div class='alert alert-danger py-2 small'>Login gagal! Periksa NIS/NIP dan Password Anda.</div>";
                    } else if($_GET['pesan'] == "logout"){
                        echo "<div class='alert alert-success py-2 small'>Anda telah berhasil logout.</div>";
                    } else if($_GET['pesan'] == "belum_login"){
                        echo "<div class='alert alert-warning py-2 small'>Silakan login untuk mengakses halaman ini.</div>";
                    }
                }
                ?>

                <form action="proses_login.php" method="POST" class="text-start mt-4" autocomplete="off">
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor ID (NIS)</label>
                        <input type="text" name="nomor_identitas" class="form-control custom-input" placeholder="Masukkan NIS / NIP" required autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="pass" class="form-control custom-input" placeholder="••••••••" required autocomplete="new-password">
                            <i class="bi bi-eye-slash toggle-password" id="iconPass" onclick="togglePass()"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                        <a href="#" class="link-forgot">Lupa Password?</a>
                    </div>

                    <button type="submit" name="login" class="btn btn-masuk">
                        Masuk Aplikasi <i class="bi bi-arrow-right-short"></i>
                    </button>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Belum punya akun? 
                            <a href="register.php" class="fw-bold text-decoration-none" style="color: var(--wood-dark);">Buat Akun Baru</a>
                        </p>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
    function togglePass() {
        const input = document.getElementById('pass');
        const icon = document.getElementById('iconPass');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>

</body>
</html>