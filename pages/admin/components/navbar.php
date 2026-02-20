<nav class="navbar-custom d-flex justify-content-between align-items-center sticky-top">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-dark border-0" id="menu-toggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <h5 class="m-0 fw-bold d-none d-md-block" style="font-family: 'Merriweather', serif; color: var(--wood-dark);">
            Administrator Panel
        </h5>
    </div>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
            <div class="text-end me-2 d-none d-md-block">
                <div class="fw-bold small"><?= $_SESSION['nama']; ?></div>
                <div class="text-muted" style="font-size: 0.7rem;">Super Admin</div>
            </div>
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
            <li><a class="dropdown-item text-danger" href="../../auth/logout.php">Keluar</a></li>
        </ul>
    </div>
</nav>