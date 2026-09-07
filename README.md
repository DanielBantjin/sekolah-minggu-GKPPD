# Tracking Renungan Sekolah Minggu GKPPD

Aplikasi web untuk mengelola renungan, reading track, kehadiran, kegiatan, keuangan, serta akun guru dan murid Sekolah Minggu GKPPD.

## Teknologi

- Next.js 16 App Router
- React 19
- TypeScript
- Prisma ORM 6
- MySQL
- JWT session cookie
- bcrypt untuk hashing password

## Prasyarat

Pastikan perangkat sudah memiliki:

- Node.js 20 atau lebih baru
- npm
- MySQL 8 atau MariaDB yang kompatibel
- Git, jika menjalankan dari repository

## Instalasi

### 1. Clone project

```bash
git clone <URL-REPOSITORY>
cd "Sekolah-Minggu Gkppd"
```

### 2. Install dependency

```bash
npm install
```

### 3. Buat database MySQL

Buat database kosong, misalnya:

```sql
CREATE DATABASE sekolah_minggu_nextjs
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

Jika MySQL menggunakan password, sesuaikan connection string pada langkah berikutnya.

### 4. Buat file environment

Salin `.env.example` menjadi `.env.local`, kemudian isi nilainya:

```env
NEXT_PUBLIC_APP_NAME="Tracking Renungan Sekolah Minggu"
DATABASE_URL="mysql://root:PASSWORD@127.0.0.1:3306/sekolah_minggu_nextjs"
AUTH_SECRET="isi-dengan-rahasia-acak-minimal-32-karakter"
```

Catatan:

- Jangan commit `.env.local`.
- `AUTH_SECRET` wajib panjang dan acak, terutama pada production.
- Jika MySQL lokal tidak memakai password, gunakan `mysql://root@127.0.0.1:3306/sekolah_minggu_nextjs`.

### 5. Siapkan struktur database

Project ini belum menyediakan folder migration Prisma. Untuk database baru, jalankan:

```bash
npx prisma generate
npx prisma db push
```

Perintah tersebut membuat tabel berdasarkan `prisma/schema.prisma`.

## Menjalankan aplikasi

### Mode development

```bash
npm run dev
```

Buka [http://localhost:3000](http://localhost:3000).

### Mode production lokal

```bash
npm run build
npm run start
```

## Login awal

Gunakan akun berikut jika akun tersebut sudah tersedia pada database:

| Role | Username | Password |
| --- | --- | --- |
| Admin | `admin.gkppd` | `admin123` |
| Guru | `guru.gkppd` | `guru123` |
| Sekretaris | `sekretaris.gkppd` | `sekretaris123` |
| Bendahara | `bendahara.gkppd` | `bendahara123` |
| Murid Kecil | `murid.kecil` | `murid123` |
| Murid Sedang | `murid.sedang` | `murid123` |
| Murid Remaja | `murid.remaja` | `murid123` |

Login dapat menggunakan username atau email. Ganti password akun awal sebelum aplikasi digunakan pada lingkungan nyata.

## Alur penggunaan berdasarkan role

### Admin

Admin memiliki akses penuh untuk:

- Mengelola role dan user
- Mengelola data murid dan kelas
- Membuat, mengubah, dan menghapus renungan
- Mengelola kegiatan, reading track, kehadiran, dan keuangan
- Melihat laporan serta ringkasan dashboard

### Guru

Guru dapat:

- Membuat dan mengelola renungan
- Mencatat kehadiran berdasarkan kelas Kecil, Sedang, dan Remaja
- Mengubah atau menghapus catatan kehadiran
- Melihat laporan reading berdasarkan tanggal dan kelas

### Sekretaris

Sekretaris memiliki akses khusus ke menu **Kegiatan** untuk:

- Membuat kegiatan
- Melihat kegiatan hari ini
- Melihat kegiatan yang akan datang
- Melihat kegiatan yang sudah lewat

### Bendahara

Bendahara memiliki akses khusus ke menu **Keuangan** untuk:

- Mencatat pemasukan dan pengeluaran
- Mengedit transaksi
- Menghapus transaksi
- Memfilter transaksi berdasarkan tanggal
- Mengunduh laporan CSV

### Murid

Murid dapat:

- Melihat renungan aktif
- Membaca dan mencatat progress renungan
- Melihat kalender kegiatan
- Memilih tanggal untuk melihat detail kegiatan

Pendaftaran murid tersedia melalui halaman **Daftar sebagai murid**. Username hanya digunakan untuk login, sedangkan nama lengkap digunakan pada data murid.

## Keamanan aplikasi

Fitur keamanan yang tersedia:

- Proteksi route berdasarkan sesi dan role
- JWT dengan issuer, audience, dan algoritma yang dibatasi
- Cookie sesi `HttpOnly` dan `SameSite=Strict`
- `Secure` cookie pada production
- Rate limit login untuk mengurangi brute-force
- Validasi input login
- Perlindungan request cross-site pada API mutasi
- Security headers seperti CSP, HSTS production, X-Frame-Options, dan `X-Content-Type-Options`

Untuk production, gunakan HTTPS, `AUTH_SECRET` yang kuat, kredensial database khusus aplikasi, serta jangan gunakan password demo.

## Perintah pengembangan

```bash
npm run dev       # Menjalankan server development
npm run build     # Memvalidasi dan membangun aplikasi production
npm run start     # Menjalankan hasil build production
npm run lint      # Menjalankan ESLint
npx prisma studio # Membuka browser database Prisma
```

## Struktur penting

```text
app/
  api/             Route handler autentikasi dan API
  admin/           Halaman admin
  teacher/         Halaman guru, sekretaris, dan bendahara
  student/         Halaman murid
  dashboard/       Dashboard berdasarkan role
lib/auth.ts        Pembuatan dan validasi session JWT
prisma/schema.prisma
proxy.ts           Proteksi request dan security headers
```

## Troubleshooting

### Database tidak tersambung

Periksa:

1. Service MySQL sedang berjalan.
2. Nama database dan port benar.
3. Username/password pada `DATABASE_URL` benar.
4. Jalankan ulang `npx prisma generate` dan `npx prisma db push`.

### Login gagal

Periksa username/email dan password. Pastikan role terkait sudah tersedia pada tabel `role` dan user memiliki `roleId` yang benar.

### Port 3000 sedang digunakan

Jalankan Next.js pada port lain:

```bash
npm run dev -- -p 3001
```

Kemudian buka [http://localhost:3001](http://localhost:3001).

## Catatan deployment

Sebelum deployment:

1. Isi `DATABASE_URL` production.
2. Buat `AUTH_SECRET` baru minimal 32 karakter.
3. Aktifkan HTTPS.
4. Jalankan `npm run build`.
5. Jalankan `npm run start` atau deploy sesuai platform hosting.
6. Ganti semua password akun awal.
