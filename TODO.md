# TODO

- [x] Migrasikan seeder akun login admin/guru/murid: pastikan idempotent dan sesuai skema `users.role_id` + FK `roles`.
- [x] Perbarui `AdminRoleSeeder.php` jika diperlukan (mis. pastikan kolom yang dipakai sesuai model/migration, dan buat role by name).
- [x] Tambahkan rute & fitur baca renungan untuk student (route `student.reading.today`).
- [x] Tambahkan rute & view laporan renungan untuk teacher (route `teacher.reading_report.today`).
- [ ] Verifikasi end-to-end: login sebagai admin/guru/murid dan pastikan role gate (`checkrole`) bekerja.
- [ ] Bersihkan backlog: rapikan `ReadingController@store` signature & pastikan tidak ada error PHP/staning setelah perubahan route.


