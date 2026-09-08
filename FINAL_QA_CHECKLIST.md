# Final QA Checklist GKPPD

Tanggal: 2026-09-07

## 1. Login dan akun demo

- Admin: `admin.gkppd` / `admin123`
- Guru: `guru.gkppd` / `guru123`
- Sekretaris: `sekretaris.gkppd` / `sekretaris123`
- Bendahara: `bendahara.gkppd` / `bendahara123`
- Murid: `murid.kecil` / `murid123`

## 2. Role access matrix

| Role | Halaman yang boleh dibuka | Halaman yang dibatasi |
| --- | --- | --- |
| Admin | Dashboard, Kelola User, Kelola Kegiatan, Kelola Keuangan | Halaman guru/sekretaris/bendahara |
| Guru | Dashboard, Renungan, Kehadiran, Laporan Reading | Keuangan, Kegiatan |
| Sekretaris | Dashboard, Kegiatan | Keuangan, Renungan |
| Bendahara | Dashboard, Keuangan | Kegiatan, Renungan |
| Murid | Dashboard, Kegiatan murid, Reading, Refleksi murid | Semua halaman guru/admin |

## 3. Hasil validasi QA

- Login berhasil untuk semua akun role utama.
- Sesi login berfungsi dengan username/email dan password yang benar.
- Redirect ke dashboard berjalan dengan benar.
- Akses tidak sah diarahkan kembali ke dashboard.
- Dashboard menampilkan module sesuai role.
- Guru baru semua dapat login dengan email profil yang diberikan.

## 4. Rincian data guru aktif

- Immoia Sirmaika Tumangger
- Daniel S F Bancin
- Chris Arken E Berutu
- Rejeki S Tumangger
- Erikson Manik
- Surynando Tinendung
- Saanggiat Lambinar Tumangger
- Rina Manik
- Sri Boangmanalu

## 5. Catatan final

- Build project berhasil: `npm run build`
- Smoke test role access berhasil.
- Semua pengujian role dan login diterima untuk tahap final QA.
