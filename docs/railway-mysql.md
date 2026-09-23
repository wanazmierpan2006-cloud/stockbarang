# Deployment Railway dengan MySQL

Konfigurasi Laravel proyek ini menggunakan MySQL. Variabel pada dashboard Railway
tetap harus diubah karena push kode tidak mengganti environment service.

1. Pilih proyek dan environment Railway yang menjalankan aplikasi ini.
2. Tambahkan service MySQL dengan volume persisten. Untuk service baru, atur
   `MYSQL_DATABASE=stockbarang` sebelum database pertama kali diinisialisasi.
   Untuk MySQL yang sudah diinisialisasi, buat database `stockbarang` terlebih
   dahulu; perubahan variabel saja tidak membuat atau mengganti nama database.
3. Pada Variables service aplikasi, terapkan `.env.railway.example`. Sesuaikan
   nama `MySQL` dalam referensi jika service database memiliki nama berbeda.
   Pastikan `MYSQLDATABASE` milik service database menunjuk `stockbarang`.
4. Pertahankan `APP_KEY` dan `APP_URL` aplikasi yang sudah ada. Hapus `DB_URL`
   lama dan ganti `DB_DATABASE` yang sebelumnya berupa path file SQLite.
5. Sebelum memutus akses SQLite, simpan salinan database lama. Jika datanya perlu
   dipertahankan, pindahkan dan verifikasi datanya sebelum mengalihkan aplikasi.
   `artisan migrate` hanya membuat skema, bukan memindahkan data SQLite ke MySQL.
6. Push perubahan ke branch yang terhubung ke Railway, kemudian deploy setelah
   MySQL dan variabel siap. Procfile menjalankan migrasi serta seeder proyek,
   lalu membuka server pada port Railway. Jika Start Command di dashboard
   mengoverride Procfile, gunakan:

   ```sh
   php artisan config:clear && php artisan migrate --seed --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

7. Periksa log migrasi, login, daftar barang, dan riwayat transaksi. Penghapusan
   barang menggunakan kolom `deleted_at` dan mempertahankan detail transaksi.

`stockbarang_testing` hanya digunakan tes lokal, bukan database aplikasi Railway.
Untuk antrean database, jalankan worker terpisah jika aplikasi memakai job antrean.

Referensi: [Railway MySQL](https://docs.railway.com/databases/mysql) dan
[Railway Variables](https://docs.railway.com/variables).
