<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['update'])) {
    $id          = $_POST['id_buku'];
    $judul       = $_POST['judul_buku'];
    $kategori    = $_POST['id_kategori'];
    $penulis     = $_POST['penulis'];
    $penerbit    = $_POST['penerbit'];      // Data Baru
    $tahun       = $_POST['tahun_terbit'];  // Data Baru
    $stok        = $_POST['stok_tersedia']; // INI YANG BIKIN STOK BERUBAH
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah user upload gambar baru?
    if ($_FILES['gambar']['name'] != "") {
        // Jika upload gambar baru
        $rand = rand();
        $ekstensi =  array('png','jpg','jpeg','gif');
        $filename = $_FILES['gambar']['name'];
        $ukuran = $_FILES['gambar']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(!in_array($ext,$ekstensi) ) {
            echo "<script>alert('Jenis gambar tidak valid!'); window.location='edit.php?id=$id';</script>";
            exit();
        } else {
            $xx = $rand.'_'.$filename;
            move_uploaded_file($_FILES['gambar']['tmp_name'], '../../../uploads/'.$xx);
            $nama_gambar = $xx;
        }
    } else {
        // Jika tidak upload, pakai gambar lama
        $nama_gambar = $gambar_lama;
    }

    // Query Update Lengkap
    $query = "UPDATE buku SET 
              judul_buku = '$judul',
              id_kategori = '$kategori',
              penulis = '$penulis',
              penerbit = '$penerbit',
              tahun_terbit = '$tahun',
              stok_tersedia = '$stok',
              gambar = '$nama_gambar'
              WHERE id_buku = '$id'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data buku berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update buku: " . mysqli_error($koneksi) . "'); window.location='edit.php?id=$id';</script>";
    }

} else {
    header("Location: index.php");
}
?>