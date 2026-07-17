# Tracking Renungan Sekolah Minggu GKPPD

Aplikasi web untuk mengelola renungan, absensi, kegiatan, dan keuangan sekolah minggu dengan antarmuka berbasis Laravel + Tailwind.

## ✅ Status Uji Coba

Aplikasi telah melalui pemeriksaan awal terhadap fitur utama, navigasi, dan tampilan antarmuka. Secara umum, fitur inti sudah dapat digunakan untuk kebutuhan operasional sehari-hari.

## 🚀 Fitur Utama

### 1. Dashboard Murid
- Membuka renungan hari ini
- Mencatat durasi pembacaan secara otomatis di background
- Melihat daftar renungan yang sudah lewat/tersedia sebelumnya
- Menyimpan status pembacaan tanpa menampilkan timer ke murid

### 2. Dashboard Guru
- Melihat laporan pembacaan renungan hari ini
- Memantau durasi dan status pembacaan murid
- Menambah, mengedit, dan menghapus renungan
- Menilai aktivitas pembacaan melalui data yang tersimpan

### 3. Dashboard Admin
- Mengelola role, user, murid, renungan, kegiatan, absensi, keuangan, dan laporan
- Mengakses ringkasan dashboard admin
- Menjalankan export Excel dan PDF laporan

### 4. Sistem Role & Akses
- Admin: akses penuh ke modul utama
- Guru: akses pada pengelolaan renungan dan laporan pembacaan
- Murid: akses pada halaman renungan yang tersedia dan riwayat yang sudah lewat

## 🧪 Hasil Uji Coba

### Yang berhasil diuji
- Halaman dashboard admin dapat dibuka dan menampilkan ringkasan data
- Halaman login dan navigasi utama berjalan normal
- Modul renungan untuk guru dapat diakses dan fitur CRUD sudah tersedia
- Modul murid dapat membuka renungan yang tersedia dan melihat renungan sebelumnya
- Tampilan utama telah diperbaiki agar teks lebih jelas dan tidak mudah menyatu dengan background

### Catatan penting
- Pengujian otomatis dengan `php artisan test` masih mengalami masalah karena environment test menggunakan SQLite dan driver yang tidak tersedia di mesin lokal (`could not find driver`).
- Untuk penggunaan sehari-hari, aplikasi sudah bisa dioperasikan melalui browser lokal dengan server Laravel berjalan.

## 🗄️ Struktur Database

Tabel utama mencakup:
- users
- roles
- students
- reflections
- reading_tracks
- attendances
- finances

## 👥 Akun Default

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| guru@example.com | password | Guru |
| murid@example.com | password | Murid |

## ⚙️ Teknologi Stack

- Laravel 12
- Tailwind CSS
- MySQL
- PHP 8.2+
- Blade + JavaScript

## 🔧 Instalasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Akses aplikasi di: http://127.0.0.1:8000

## 📱 Panduan Penggunaan

### Untuk Murid
1. Login menggunakan akun murid
2. Buka menu renungan hari ini dari dashboard
3. Baca renungan; durasi akan tercatat otomatis di background
4. Untuk melihat renungan sebelumnya, buka menu Renungan Sebelumnya

### Untuk Guru
1. Login menggunakan akun guru
2. Buka menu Kelola Renungan
3. Tambah, edit, atau hapus renungan sesuai kebutuhan
4. Buka laporan pembacaan untuk melihat status murid

### Untuk Admin
1. Login menggunakan akun admin
2. Kelola data master dan operasional melalui menu admin
3. Lihat dashboard ringkasan, absensi, keuangan, dan kegiatan
4. Gunakan export Excel/PDF jika diperlukan

## 🔐 Keamanan

- Authentication Laravel Breeze
- Role-based access control
- CSRF protection
- Password hashing dengan bcrypt

## 📝 Route Utama

- `/dashboard` - dashboard sesuai role
- `/student/reading/today` - halaman renungan murid hari ini
- `/student/reflections` - daftar renungan sebelumnya
- `/teacher/reflections` - pengelolaan renungan guru
- `/teacher/reading-report/today` - laporan pembacaan guru
- `/admin/dashboard` - dashboard admin

## 🐛 Troubleshooting

### Jika database error
```bash
php artisan migrate:fresh --seed
```

### Jika tampilan tidak update
```bash
php artisan view:clear
php artisan config:clear
```

### Jika ingin menjalankan build ulang Tailwind
```bash
npm run build
```

## 📧 Catatan

Aplikasi ini sudah dapat digunakan untuk kebutuhan operasional sekolah minggu, terutama untuk sistem renungan, monitoring pembacaan, absensi, kegiatan, dan keuangan. Untuk skala yang lebih besar, disarankan dilakukan pengujian lanjutan dengan data nyata dan lingkungan staging sebelum digunakan penuh oleh tim operasional.
