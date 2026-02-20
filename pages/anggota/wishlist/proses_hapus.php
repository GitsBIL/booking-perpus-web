<?php
include '../../../config/koneksi.php';

if(isset($_GET['id'])){
    $id_wishlist = $_GET['id'];
    
    $hapus = mysqli_query($koneksi, "DELETE FROM wishlist WHERE id_wishlist='$id_wishlist'");
    
    if($hapus){
        echo "<script>
            alert('Buku dihapus dari koleksi.'); 
            window.location='index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus.'); 
            window.location='index.php';
        </script>";
    }
}
?>