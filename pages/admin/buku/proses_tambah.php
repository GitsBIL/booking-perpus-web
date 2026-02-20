<?php
include '../../../config/koneksi.php';
include '../../../config/auth.php';
checkLogin();
checkRole('admin');

if (isset($_POST['simpan'])) {
    // 1. Buat Kode Buku Otomatis (Format: BK + Angka Acak 5 digit)
    // Contoh hasil: BK-58291
    $kode_buku   = "BK-" . rand(10000, 99999); 

    $judul       = $_POST['judul_buku'];
    $kategori    = $_POST['id_kategori'];
    $penulis     = $_POST['penulis'];
    $penerbit    = $_POST['penerbit'];
    $tahun       = $_POST['tahun_terbit'];
    $stok        = $_POST['stok_tersedia'];
    
    // Upload Gambar
    $rand = rand();
    $ekstensi =  array('png','jpg','jpeg','gif');
    $filename = $_FILES['gambar']['name'];
    $ukuran = $_FILES['gambar']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(!in_array($ext,$ekstensi) ) {
        echo "<script>alert('Jenis gambar tidak valid!'); window.location='tambah.php';</script>";
        exit();
    } else {
        if($ukuran < 2044070){		
            $xx = $rand.'_'.$filename;
            move_uploaded_file($_FILES['gambar']['tmp_name'], '../../../uploads/'.$xx);
            
            // 2. Masukkan $kode_buku ke dalam Query
            $query = "INSERT INTO buku (kode_buku, judul_buku, id_kategori, penulis, penerbit, tahun_terbit, stok_tersedia, gambar) 
                      VALUES ('$kode_buku', '$judul', '$kategori', '$penulis', '$penerbit', '$tahun', '$stok', '$xx')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Buku berhasil ditambahkan!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Gagal menambah buku: " . mysqli_error($koneksi) . "'); window.location='tambah.php';</script>";
            }
        } else {
            echo "<script>alert('Ukuran gambar terlalu besar!'); window.location='tambah.php';</script>";
        }
    }
} else {
    header("Location: index.php");
}
?>