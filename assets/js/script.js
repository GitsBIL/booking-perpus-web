document.addEventListener("DOMContentLoaded", function() {
    
    // Ambil elemen wrapper utama
    var el = document.getElementById("wrapper");
    // Ambil tombol toggle menu
    var toggleButton = document.getElementById("menu-toggle");

    // Cek apakah tombol ada (untuk menghindari error di halaman login)
    if (toggleButton) {
        toggleButton.onclick = function () {
            // Tambahkan/Hapus class 'toggled' pada wrapper
            el.classList.toggle("toggled");
        };
    }
});