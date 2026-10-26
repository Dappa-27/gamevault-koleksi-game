# GameVault - Sistem Informasi Koleksi Game

Aplikasi manajemen koleksi game (PHP murni + MySQL/MariaDB) dengan login berbasis `$_SESSION` dan antarmuka dwibahasa (Indonesia / English).

## Fitur
- Login & logout (`$_SESSION`, `password_hash`/`password_verify`, `session_regenerate_id`)
- CRUD data game: judul, platform, genre, status bermain, tahun rilis
- Validasi form dengan `if-else` dan `switch`, sanitasi dengan `trim`, `strip_tags`, `filter_var`, dan `htmlspecialchars`
- Prepared statement PDO (anti SQL injection), token CSRF pada semua form POST
- Pencarian judul dan filter status
- Pergantian bahasa ID/EN yang disimpan di `$_SESSION['lang']`

## Cara menjalankan
1. Pasang XAMPP / Laragon (PHP 8.0+ dan MySQL/MariaDB).
2. Salin folder proyek ke `htdocs` (XAMPP) atau `www` (Laragon).
3. Buka phpMyAdmin, import `database/gamevault.sql` (database `gamevault` akan dibuat otomatis).
4. Sesuaikan kredensial di `config/database.php` bila perlu (default: user `root`, tanpa password).
5. Buka `http://localhost/gamevault/login.php`.

Akun demo: **admin** / **admin123**

## Struktur folder
```
gamevault/
├── assets/style.css        # tampilan
├── config/database.php     # koneksi PDO
├── database/gamevault.sql  # skema + data contoh
├── includes/               # bootstrap, header, footer, partial form
├── lang/id.php, en.php     # kamus terjemahan
├── login.php, logout.php, set_lang.php
├── index.php               # Read (tabel, cari, filter)
├── create.php              # Create
├── edit.php                # Update
└── delete.php              # Delete
```

## Alur program
1. Semua halaman memuat `includes/bootstrap.php` (mulai session, atur bahasa, helper, validasi).
2. Halaman CRUD memanggil `require_login()`; tanpa `$_SESSION['user_id']` pengguna dialihkan ke `login.php`.
3. Login memeriksa username di tabel `users`, lalu `password_verify`. Jika cocok, session dibuat.
4. Create/Update: form POST -> cek CSRF -> `validate_game()` -> jika tidak ada error, simpan dengan prepared statement -> redirect dengan pesan flash.
5. Delete: tombol di tabel mengirim POST + token CSRF ke `delete.php`.
6. Tombol ID/EN memanggil `set_lang.php` yang menyimpan pilihan ke `$_SESSION['lang']`, lalu kembali ke halaman sebelumnya.
