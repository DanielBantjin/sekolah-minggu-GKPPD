# Next.js di Vercel

## Konfigurasi project Vercel

1. Import repository `sekolah-minggu-GKPPD` ke Vercel.
2. Biarkan **Root Directory** pada root repository.
3. Framework preset akan terdeteksi sebagai **Next.js**.
4. Build command: `npm run build`.
5. Tambahkan environment variables dari `next-app/.env.example` di Vercel.

## Status migrasi

Aplikasi ini sekarang menggunakan Next.js sebagai framework utama. Halaman login dan identitas visual sudah tersedia di root project.

Autentikasi, database, CRUD admin, upload gambar, tracking bacaan, dan laporan perlu diimplementasikan sebagai service Next.js sebelum fitur production tersebut dapat digunakan kembali.