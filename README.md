# MOTOFIX — Bengkel Orange (Laravel 12)

Paket perbaikan lanjutan untuk project Laravel MOTOFIX yang sudah ada. Ini adalah **overlay**, bukan instalasi Laravel lengkap.

## Cara menggabungkan
1. Backup folder project dan database MySQL `motofix`.
2. Salin isi folder `ProjectMotofixx` dari ZIP ini ke folder project lokal yang berisi file `artisan`.
3. Pilih Replace untuk file yang sama. Jangan mengganti `.env`, `vendor`, `node_modules`, atau konfigurasi lokal yang tidak disertakan.
4. Jalankan di terminal project:
   ```bash
   php artisan optimize:clear
   composer dump-autoload
   php artisan migrate
   php artisan db:seed --class=SparepartCatalogSeeder
   npm install
   npm run build
   ```
5. Jika ingin membuat akun admin baru, atur `MOTOFIX_ADMIN_EMAIL` dan `MOTOFIX_ADMIN_PASSWORD` di `.env`, lalu jalankan `php artisan db:seed --class=AdminUserSeeder`.

**Jangan jalankan `php artisan migrate:fresh` pada database berisi data penting**, karena perintah tersebut menghapus semua tabel.

## Perubahan utama
- AdminController dan dashboard admin untuk statistik, booking, status pembayaran, CRUD layanan, dan CRUD sparepart.
- Chart booking, status pengerjaan, dan pendapatan berdasarkan data database.
- Booking langsung diarahkan ke invoice dinamis yang dapat dicetak atau disimpan sebagai PDF.
- Migration aman untuk kolom metode pembayaran dan pemulihan tabel pesanan sparepart yang belum ada.
- Katalog sparepart lebih banyak, stok tetap dipertahankan untuk item yang sudah ada.
- Beranda ditambah katalog unggulan, FAQ interaktif, efek hover, dan tautan yang lebih jelas.
- Logout memakai POST dengan CSRF.

## Catatan
- Kode diperiksa secara statis dan semua file PHP lulus `php -l` pada lingkungan pembuatan paket, tetapi belum diuji end-to-end dengan database dan `.env` lokal pengguna.
- `npm run build` diperlukan untuk membuat `public/build/manifest.json` pada halaman yang menggunakan `@vite`.
- QRIS/transfer/debit masih tercatat sebagai metode pembayaran; belum terhubung ke payment gateway.
