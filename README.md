# PengliCraft - Penglipuran Rumah41

Aplikasi pengelolaan produk berbasis PHP Native, MySQL, HTML, CSS, dan JavaScript dengan pola MVC ringan.

## Instalasi lokal

1. Jalankan Apache dan MySQL melalui Laragon.
2. Import [`database/penglicraft.sql`](database/penglicraft.sql) melalui HeidiSQL/phpMyAdmin.
3. Sesuaikan `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS` di [`config/database.php`](config/database.php) jika konfigurasi MySQL berbeda.
4. Buka `http://localhost/rumah-41/auth/setup_admin.php`.
5. Buat akun admin dengan password minimal 8 karakter. Halaman setup hanya menerima koneksi lokal dan membuat `storage/admin_setup.lock` setelah berhasil. Jangan hapus lock tersebut pada instalasi normal.
6. Login melalui `http://localhost/rumah-41/login.php`.

Password hanya disimpan sebagai hasil `password_hash()` dan diverifikasi dengan `password_verify()`. Login memakai prepared statement, session yang aman, regenerasi session ID, dan pesan error yang tidak membocorkan detail akun.

## Struktur MVC

- `models/Product.php`: akses data produk.
- `controllers/ProductController.php`: validasi input dan orkestrasi use case produk.
- `views/`: ruang untuk template view lanjutan; template bersama saat ini berada di `includes/`.
- `auth/`: login, logout, proteksi route, dan setup admin.
- `assets/`: CSS mobile-first dan JavaScript interaksi menu/password.
- Gambar produk diunggah ke `assets/uploads/` dengan format JPG, PNG, atau WEBP dan ukuran maksimal 5 MB.

## Pengujian wajib

Dengan Apache/MySQL aktif, lakukan:

1. Setup akun lalu login dengan username/email dan password valid: harus menuju dashboard.
2. Login dengan password salah: harus kembali ke login dengan pesan umum “Username/email atau password salah.”
3. Buka `dashboard.php` tanpa login: harus diarahkan ke `login.php`.
4. Logout, lalu buka dashboard: harus kembali diarahkan ke login.
5. Setelah login, tambah produk: produk muncul di Data Produk.
6. Edit produk: perubahan tersimpan dan tampil di daftar.
7. Uji browser responsive pada lebar 360px, 390px, 768px, dan 1366px. Pada layar kecil sidebar menjadi drawer, form menjadi satu kolom, dan tabel dapat digeser horizontal.
8. Pastikan kolom `users.password` berisi hash (diawali `$2y$` atau format hash PHP lain), bukan password asli. Jangan memasukkan password nyata ke log atau source code.

Lint statis yang digunakan:

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

## Akses melalui HP pada Wi-Fi yang sama

PHP dan MySQL berjalan di laptop/server, bukan langsung di browser HP. Pastikan Apache dikonfigurasi menerima koneksi jaringan lokal, kemudian cari IP laptop (contoh `ipconfig`, misalnya `192.168.1.20`). Izinkan hanya port Apache yang diperlukan pada Windows Firewall. Dari HP yang berada di Wi-Fi yang sama, buka:

`http://192.168.1.20/rumah-41/login.php`

Gunakan port Apache jika tidak memakai port 80, misalnya `http://192.168.1.20:8080/rumah-41/login.php`. Untuk akses internet di luar jaringan lokal, deploy ke hosting yang mendukung PHP/MySQL, gunakan domain, kredensial database melalui environment/konfigurasi server, dan aktifkan HTTPS.
