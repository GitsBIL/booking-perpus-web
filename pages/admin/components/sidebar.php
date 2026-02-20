<?php
// Deteksi Page Aktif untuk Highlight Menu
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-2 border-bottom border-white-50">
        <img src="/PerpusDigital/assets/img/icon_PerpusDigital.png" width="65" class="d-block mx-auto" style="filter: brightness(0) invert(1);">
    </div>
    
    <div class="list-group list-group-flush mt-2">
        <a href="/PerpusDigital/pages/admin/dashboard.php" 
           class="list-group-item list-group-item-action <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <div class="small text-white-50 px-4 mt-4 mb-2 text-uppercase fw-bold" style="font-size:0.75rem; letter-spacing: 1px;">Master Data</div>
        
        <a href="/PerpusDigital/pages/admin/buku/index.php" 
           class="list-group-item list-group-item-action <?= ($current_dir == 'buku') ? 'active' : ''; ?>">
            <i class="bi bi-book-half"></i> Data Buku
        </a>
        <a href="/PerpusDigital/pages/admin/kategori/index.php" 
           class="list-group-item list-group-item-action <?= ($current_dir == 'kategori') ? 'active' : ''; ?>">
            <i class="bi bi-tags-fill"></i> Kategori
        </a>
        <a href="/PerpusDigital/pages/admin/anggota/index.php" 
           class="list-group-item list-group-item-action <?= ($current_dir == 'anggota') ? 'active' : ''; ?>">
            <i class="bi bi-people-fill"></i> Data Anggota
        </a>

        <div class="small text-white-50 px-4 mt-4 mb-2 text-uppercase fw-bold" style="font-size:0.75rem; letter-spacing: 1px;">Sirkulasi</div>

        <a href="/PerpusDigital/pages/admin/transaksi/index.php" 
           class="list-group-item list-group-item-action <?= ($current_dir == 'transaksi') ? 'active' : ''; ?>">
            <i class="bi bi-arrow-left-right"></i> Transaksi
        </a>
        <a href="/PerpusDigital/pages/admin/laporan/index.php" 
           class="list-group-item list-group-item-action <?= ($current_dir == 'laporan') ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
        </a>
        
        <div class="mt-5 px-3 pb-4">
            <a href="/PerpusDigital/pages/auth/logout.php" class="btn btn-outline-light w-100 py-2" style="border-color: rgba(255,255,255,0.2);">
                <i class="bi bi-box-arrow-left me-2"></i> Keluar
            </a>
        </div>
    </div>
</div>