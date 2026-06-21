# <p align="center">📖 Aksara Digital Publishing CMS</p>

<p align="center">
  <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=1000&auto=format&fit=crop&q=80" alt="Aksara Banner" width="100%" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" />
</p>

---

**Aksara** adalah sebuah platform *Content Management System (CMS) Digital Publishing* modern yang dirancang untuk menghadirkan pengalaman membaca, menulis, dan kurasi konten yang premium, dinamis, dan terstruktur. Aplikasi ini dibangun di atas fondasi **Laravel 11**, **Tailwind CSS**, **Alpine.js**, dan database **MySQL**.

Sistem ini menerapkan **Role-Based Access Control (RBAC)** penuh yang membagi akses pengguna menjadi: **Tamu**, **Reader**, **Author (Penulis)**, **Editor (Redaksi)**, dan **Admin (System Administrator)**.

---

## 🌟 Fitur Utama (Premium Highlights)

*   📊 **Dashboard Admin Visual (New):** Menyajikan data statistik real-time platform secara visual (Total Artikel, E-Book, Like, Komentar, & Pengguna), diagram persebaran kategori, aktivitas konten terbaru, serta daftar pendaftar terbaru dalam tata letak kartu gradien yang modern.
*   👥 **Manajemen Pengguna Terpusat (Admin):** Hak akses penuh bagi Admin untuk mengelola akun pengguna, mengubah peran (*role*), mendaftarkan staf redaksi baru (*Author/Editor*), serta menghapus akun secara aman.
*   📝 **Alur Kerja Redaksi (Author & Editor):** 
    *   **Author:** Menulis draf artikel, mengunggah E-Book (berkas PDF & sampul), dan mengajukannya ke redaksi.
    *   **Editor:** Mengulas antrean naskah yang masuk (*Review Queue*), menyetujui penerbitan (*Publish*), atau mengembalikannya ke draf disertai catatan revisi khusus.
*   ⚡ **Interaksi AJAX Mulus & Cepat:** Fitur **Like (Apresiasi)**, **Bookmark (Simpan Bacaan)**, dan **Komentar (Balas Masukan)** secara dinamis tanpa memuat ulang halaman (*zero page refresh*), memanfaatkan integrasi khusus Alpine.js dan Laravel API.
*   🌐 **Seeder Berbasis API Asli & Pemulihan Otomatis:**
    *   Mengambil konten artikel secara dinamis dari **Dev.to API** untuk menyajikan artikel pemrograman/teknologi asli yang panjang, rapi, dan variatif.
    *   Secara otomatis mengunduh e-book PDF asli dari internet (seperti panduan aksesibilitas W3C) dan memulihkan data tersebut dari folder penyimpanan (*storage auto-recovery*) setiap kali database di-seed.
*   📖 **Pengalaman Membaca Premium:** Memanfaatkan modul `@tailwindcss/typography` kustom untuk rendering artikel yang bersih (layaknya Medium/Substack), dengan blok kode (*code blocks*) bergaya editor IDE, kutipan artistik, dan layout ramah pembaca.

---

## 👥 Matriks Hak Akses (RBAC Matrix)

| Modul / Fungsionalitas Sistem | Tamu | Reader | Author | Editor | Admin |
| :--- | :---: | :---: | :---: | :---: | :---: |
| Membaca Konten Terbit (*Published*) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Menggunakan Fitur Pencarian & Filter | ✓ | ✓ | ✓ | ✓ | ✓ |
| Melakukan Registrasi & Autentikasi | ✓ | ✓ | ✓ | ✓ | ✓ |
| Memberikan Like, Bookmark, & Komentar | X | ✓ | ✓ | ✓ | ✓ |
| Menulis & Mengedit Artikel Pribadi (*Draft*) | X | X | ✓ | X | X |
| Mengunggah Berkas PDF E-Book & Cover | X | X | ✓ | X | X |
| Mengajukan Draf ke Redaksi (*Submit*) | X | X | ✓ | X | X |
| Melihat Antrean Naskah Masuk (*Review Queue*) | X | X | X | ✓ | X |
| Menyetujui Publikasi (*Publish*) | X | X | X | ✓ | X |
| Mengembalikan Naskah & Menulis Revisi | X | X | X | ✓ | X |
| Mengelola Akun Pengguna & Hak Akses | X | X | X | X | ✓ |

---

## 🛠️ Prasyarat System

Pastikan perangkat Anda sudah terinstal:
*   **PHP 8.2** atau lebih tinggi
*   **Composer**
*   **Node.js & NPM**
*   **MySQL Server** (XAMPP, Laragon, Herd, dsb.)

---

## 📦 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi Aksara di server lokal Anda:

### 1. Kloning & Masuk ke Direktori Proyek
```bash
git clone https://github.com/Rombon07/kabarkini-cms.git aksara
cd aksara
```

### 2. Instalasi Dependensi
Instal pustaka backend (PHP) dan frontend (JS/CSS):
```bash
composer install
npm install
```

### 3. Konfigurasi Database (.env)
1. Salin file contoh konfigurasi env:
   ```bash
   cp .env.example .env
   ```
2. Buat database baru bernama `db_publisher_media` di MySQL Anda.
3. Buka file `.env` baru Anda dan sesuaikan konfigurasi database Anda (nama DB, username, password).

### 4. Jalankan Migrasi & Database Seeder
Langkah ini akan membuat seluruh tabel, men-download file PDF asli dari internet, mengambil artikel dari Dev.to API, serta mendaftarkan akun uji coba secara otomatis.
```bash
php artisan migrate:fresh --seed
```

### 5. Hubungkan Folder Storage
```bash
php artisan storage:link
```

### 6. Jalankan Server Pengembangan
Jalankan Vite asset bundler di terminal pertama:
```bash
npm run dev
```
Buka terminal baru di direktori yang sama dan jalankan server lokal Laravel:
```bash
php artisan serve
```

Aplikasi kini dapat diakses melalui browser di alamat: [**http://localhost:8000**](http://localhost:8000).

---

## 🧪 Akun Uji Coba (Test Accounts)

Gunakan akun-akun berikut untuk masuk ke dashboard masing-masing peran melalui halaman `/login`:

1.  **System Admin**
    *   **Email:** `admin@aksara.com`
    *   **Password:** `password`
    *   *Fungsi:* Mengakses statistik platform, mengelola semua pengguna, mendaftarkan staf, dan mengubah hak akses (role).
2.  **Editor (Redaksi)**
    *   **Email:** `editor@aksara.com`
    *   **Password:** `password`
    *   *Fungsi:* Memantau antrean artikel masuk, memberikan catatan revisi, dan menyetujui penerbitan artikel/ebook.
3.  **Author (Penulis)**
    *   **Email:** `author@aksara.com`
    *   **Password:** `password`
    *   *Fungsi:* Menulis draf, mengunggah naskah E-book, dan mengirimkannya ke redaksi untuk diulas.
4.  **General Reader (Pembaca)**
    *   **Email:** `reader@aksara.com`
    *   **Password:** `password`
    *   *Fungsi:* Menyukai artikel secara langsung, meninggalkan tanggapan, dan menyimpan bacaan ke halaman Library pribadi.
