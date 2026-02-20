<?php
$current_uri = $_SERVER['REQUEST_URI'];
?>

<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-2 border-bottom border-white-50">
        <img src="/PerpusDigital/assets/img/icon_PerpusDigital.png" width="65" class="d-block mx-auto" style="filter: brightness(0) invert(1);">
    </div>
    
    <div class="list-group list-group-flush mt-2">
        <a href="/PerpusDigital/pages/anggota/dashboard.php" 
           class="list-group-item list-group-item-action <?= (strpos($current_uri, '/dashboard.php') !== false) ? 'active' : ''; ?>">
            <i class="bi bi-house-door-fill"></i> Beranda Pustaka
        </a>
        
        <div class="small text-white-50 px-4 mt-4 mb-2 text-uppercase fw-bold" style="font-size:0.75rem; letter-spacing: 1px;">Eksplorasi</div>
        
        <a href="/PerpusDigital/pages/anggota/katalog.php" 
           class="list-group-item list-group-item-action <?= (strpos($current_uri, '/katalog.php') !== false || strpos($current_uri, '/buku/') !== false) ? 'active' : ''; ?>">
            <i class="bi bi-journal-bookmark-fill"></i> Jelajah Buku
        </a>

        <a href="/PerpusDigital/pages/anggota/wishlist/index.php" 
           class="list-group-item list-group-item-action <?= (strpos($current_uri, '/wishlist/') !== false) ? 'active' : ''; ?>">
            <i class="bi bi-heart-fill"></i> Koleksi Favorit
        </a>

        <div class="small text-white-50 px-4 mt-4 mb-2 text-uppercase fw-bold" style="font-size:0.75rem; letter-spacing: 1px;">Personal</div>

        <a href="/PerpusDigital/pages/anggota/transaksi/riwayat.php" 
           class="list-group-item list-group-item-action <?= (strpos($current_uri, '/transaksi/') !== false) ? 'active' : ''; ?>">
            <i class="bi bi-clock-history"></i> Riwayat Pinjam
        </a>

        <a href="/PerpusDigital/pages/anggota/profil/index.php" 
           class="list-group-item list-group-item-action <?= (strpos($current_uri, '/profil/') !== false) ? 'active' : ''; ?>">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        
        <div class="mt-5 px-3 pb-4">
            <a href="/PerpusDigital/pages/auth/logout.php" class="btn btn-outline-light w-100 py-2" style="border-color: rgba(255,255,255,0.2);">
                <i class="bi bi-box-arrow-left me-2"></i> Keluar
            </a>
        </div>
    </div>
</div>