# MOTOFIX — paket perbaikan lanjutan

Paket overlay untuk menggabungkan pembaruan ke project Laravel yang sudah ada. Backup folder project dan database sebelum mulai.

## Perbaikan dalam paket
- Menambahkan `AdminController` dengan pemeriksaan role admin, statistik dashboard, CRUD layanan/sparepart, dan pembaruan status pembayaran.
- Menambahkan dashboard admin dengan tabel booking, tombol invoice, dan formulir pengelolaan katalog.
- Memperbaiki total pendapatan agar menghitung booking berstatus pembayaran `lunas` melalui kolom `status_pembayaran`, bukan kolom `status` yang tidak ada di tabel `bookings`.
- Menambahkan invoice booking dinamis yang hanya bisa dilihat pemilik booking atau admin, dengan tombol cetak/Save as PDF.
- Setelah booking berhasil dibuat, pelanggan diarahkan langsung ke invoice.
- Menambahkan kolom `payment_method` melalui migration aman jika kolom belum tersedia.
- Menambahkan migration pemulihan tabel sparepart orders jika tabel belum ada meski migration lama pernah tercatat.
- Menambahkan katalog sparepart tambahan dengan seeder yang tidak menimpa stok produk yang sudah ada.
- Menambahkan bagian sparepart unggulan dan FAQ interaktif di beranda.

## Cara memasang
1. Backup database `motofix` dan folder `MotoFixx`.
2. Ekstrak ZIP dan salin isi folder `ProjectMotofixx` ke root project Laravel yang berisi `artisan`. Pilih Replace untuk file yang sama.
3. Jangan timpa `.env`, `vendor`, `node_modules`, atau file konfigurasi lokal.
4. Jalankan dari terminal pada root project:
   ```bash
   php artisan optimize:clear
   composer dump-autoload
   php artisan migrate
   php artisan db:seed --class=SparepartCatalogSeeder
   npm install
   npm run build
   ```
5. Cek route: `php artisan route:list` dan buka website kembali.

**Jangan menjalankan `php artisan migrate:fresh`** pada database yang berisi data penting. Migration tambahan memeriksa apakah kolom/tabel sudah ada sebelum membuatnya.

## Catatan penting
- Kode sudah diperiksa secara statis, tetapi belum diuji end-to-end dengan database, versi dependency, dan `.env` pada komputer pengguna. Uji lokal tetap diperlukan.
- `npm run build` membuat `public/build/manifest.json` untuk halaman yang menggunakan `@vite`. Pastikan Node.js/npm terpasang dan `package.json` project Laravel tetap tersedia.
- Pembayaran QRIS/transfer/debit masih hanya dicatat sebagai metode; belum terhubung ke payment gateway.
- Chart menggunakan data booking yang sudah tersimpan. Jika database belum memiliki booking, grafik dapat menunjukkan nilai nol.
- Untuk akun admin, gunakan akun admin yang sudah ada. Jika belum ada, jalankan `php artisan db:seed --class=AdminUserSeeder` setelah memastikan konfigurasi email/password admin di `.env`.
