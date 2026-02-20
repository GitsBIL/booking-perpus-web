# 📚 Book Ing - Sistem Informasi Manajemen Perpustakaan Digital

Project Pemenuhan Tugas UAS Rekayasa Perangkat Lunak - Universitas Pelita Bangsa.

🌐 **Live Demo Application:** [bookbukuing.my.id](https://bookbukuing.my.id/)

---

## ✨ Fitur Utama
- **Multi-Role Access:** Pemisahan hak akses antara Admin dan Anggota.
- **Manajemen Buku (CRUD):** Tambah, Edit, Hapus data buku beserta pemantauan ketersediaan stok fisik secara *real-time*.
- **Sirkulasi Cerdas:** Peminjaman dan pengembalian buku dengan validasi ketersediaan stok di database.
- **Kalkulasi Denda Otomatis:** Perhitungan denda keterlambatan secara otomatis berdasarkan selisih waktu (Rp 1.000/hari).
- **Interactive UI:** Terintegrasi dengan *SweetAlert2* untuk notifikasi aksi yang elegan tanpa harus *reload* halaman.

---

## 🚀 Cara Menjalankan Project di Localhost (XAMPP)

1. *Clone* repositori ini atau *Download ZIP*, lalu ekstrak.
2. Pindahkan folder project (misal: `booking-perpus-web`) ke dalam direktori `C:\xampp\htdocs\`.
3. Buka **XAMPP Control Panel**, lalu klik *Start* pada modul **Apache** dan **MySQL**.
4. Buka browser dan akses `http://localhost/phpmyadmin/`.
5. Buat database baru, misalnya dengan nama `perpus`.
6. Pilih tab **Import**, lalu *upload* file `db_perpus_web.sql` yang ada di dalam repositori ini.
7. Buka file `config/koneksi.php` menggunakan *code editor* (seperti VS Code), lalu sesuaikan konfigurasi databasenya dengan *localhost* Anda:
   ```php
   $server   = "localhost";
   $username = "root";
   $password = "";
   $database = "perpus"; // Sesuaikan dengan nama database yang baru dibuat
   $base_url = "http://localhost/booking-perpus-web/"; // Sesuaikan dengan nama folder di htdocs
8. Buka browser dan jalankan aplikasi: http://localhost/booking-perpus-web/

🔐 Kredensial Login (Default)
Gunakan data berikut untuk mencoba masuk ke dalam sistem:

👨‍💻 Akses Administrator
Dapat melakukan kelola data buku, verifikasi pengembalian, dan cek denda.

Username: 99999999

Password: admin123

👤 Akses Anggota / User
Dapat melakukan pencarian katalog buku, melihat riwayat pinjam, dan meminjam buku.

Username: 312410376

Password: 009312410376
