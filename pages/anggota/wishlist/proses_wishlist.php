<?php
session_start();
include '../../../config/koneksi.php';

// Cek Login
if($_SESSION['status'] != "login" || $_SESSION['role'] != "anggota"){
    header("location:../../auth/login.php?pesan=belum_login");
    exit();
}

if($_POST){
    $id_user = $_SESSION['id_user'];
    $id_buku = $_POST['id_buku'];

    // 1. Cek Duplikat (Apakah sudah pernah disimpan?)
    $cek_wishlist = mysqli_query($koneksi, "SELECT * FROM wishlist WHERE id_user='$id_user' AND id_buku='$id_buku'");
    
    if(mysqli_num_rows($cek_wishlist) > 0){
        echo "<script>
            alert('Buku ini sudah ada di koleksi favoritmu!');
            window.location='index.php';
        </script>";
    } else {
        // 2. Simpan ke Database
        $query = mysqli_query($koneksi, "INSERT INTO wishlist (id_user, id_buku) VALUES ('$id_user', '$id_buku')");

        if($query){
            echo "<script>
                alert('Buku berhasil ditambahkan ke favorit! ❤️');
                window.location='index.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menyimpan ke favorit.');
                window.location='../dashboard.php';
            </script>";
        }
    }
}
?>