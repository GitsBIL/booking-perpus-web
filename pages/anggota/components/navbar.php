<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="menu-toggle"><i class="bi bi-list"></i></button>
        
        <h5 class="ms-3 mb-0 fw-bold text-secondary d-none d-md-block">Member Area</h5>

        <div class="ms-auto d-flex align-items-center gap-3">
            <div class="border-start ps-3 d-flex align-items-center gap-2">
                <div class="text-end lh-1">
                    <span class="d-block fw-bold small text-dark"><?= $_SESSION['nama']; ?></span>
                    <span class="d-block text-muted" style="font-size: 0.75rem;">Anggota Perpustakaan</span>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['nama']; ?>&background=A16E4B&color=fff" class="rounded-circle shadow-sm" width="38">
            </div>
        </div>
    </div>
</nav>