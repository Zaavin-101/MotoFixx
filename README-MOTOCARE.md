# MotoCare — Laravel full-stack (Blade, tanpa React)

Tema gelap-oranye untuk website servis motor. Laravel tetap menjadi root project, sehingga tidak perlu folder backend/frontend terpisah dan tidak memakai React. Frontend dirender dengan Blade + CSS + JavaScript biasa.

## Fitur yang tersedia
- Beranda publik, tema orange gelap, responsif, menu hamburger.
- Login/register Laravel; beranda bisa dilihat tanpa akun.
- Tombol booking dan pembelian mengarahkan user untuk login/register.
- Dashboard berbeda untuk role `customer` dan `admin`.
- Form preview booking: merek/tipe motor, plat nomor, paket, tanggal, mekanik, keluhan.
- Katalog sparepart contoh dengan kategori (oli, pengereman, kelistrikan, filter/mesin), gambar dan harga.
- Tampilan contoh tracking progres servis dan nota digital.
- Artikel perawatan, jadwal ganti oli, cek rem/ban dan CVT.
- API Laravel untuk autentikasi Sanctum, motor customer, booking, tracking, sparepart, checkout, invoice, admin dashboard dan CRUD sparepart.
- Seeder contoh brand/model motor, paket, mekanik, sparepart, artikel serta akun demo.

## Persyaratan
- PHP 8.2 atau lebih baru
- Composer 2
- MySQL/MariaDB (atau SQLite dengan konfigurasi .env)
- Node.js + npm (hanya untuk build aset Laravel; UI Blade tidak memakai React)

## Cara menjalankan di Windows (XAMPP + terminal)
1. Extract ZIP, masuk ke folder `MotoFixx` (folder yang berisi `artisan` dan `composer.json`).
2. Install dependency:
   ```bash
   composer install
   ```
3. Salin `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   ```
4. Buat database MySQL bernama `motocare`, lalu sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
5. Generate key:
   ```bash
   php artisan key:generate
   ```
6. Migrasi + data contoh:
   ```bash
   php artisan migrate --seed
   ```
7. Jalankan website:
   ```bash
   php artisan serve
   ```
8. Buka `http://127.0.0.1:8000`.

Jika ingin compile aset Laravel standar, jalankan `npm install` lalu `npm run build`. Untuk halaman Blade yang disediakan, tampilan inti menggunakan CSS inline dan bisa langsung dicoba setelah Laravel berjalan.

## Akun demo setelah `migrate --seed`
- Admin: `admin@motocare.test` / `password`
- Customer: `customer@motocare.test` / `password`

Ganti password akun demo sebelum deploy ke internet. Untuk membuat admin baru, ubah role user menjadi `admin` melalui database atau seeder; jangan menyediakan pilihan role admin di form register publik.

## API utama
- `GET /api/home`
- `POST /api/login`, `POST /api/register`
- `GET /api/spareparts`, `GET /api/spareparts/{id}`
- Dengan autentikasi Sanctum: `/api/motors`, `/api/booking-data`, `/api/bookings`, `/api/bookings/{id}/tracking`, `/api/checkout`, `/api/invoices/{id}`
- Admin: `/api/admin/dashboard`, `/api/admin/bookings`, `/api/admin/spareparts`, `/api/admin/services`, `/api/admin/mechanics`

## Catatan penting
- Foto produk/hero pada tampilan publik memakai URL gambar Unsplash sehingga butuh internet; ganti dengan file lokal di `public/images` jika ingin offline.
- Angka dashboard, status tracking, kartu produk, dan nota pada halaman Blade merupakan **data contoh untuk tampilan**, bukan transaksi sungguhan. API dan skema database menjadi fondasi backend, tetapi beberapa alur pembayaran masih perlu integrasi provider (misalnya Midtrans/Xendit), dan update tracking real-time memerlukan polling/WebSocket serta pencatatan progres dari panel admin.
- Sebelum produksi, aktifkan HTTPS, konfigurasi email, validasi stok/transaksi, authorization policy, logging pembayaran, backup, serta pengujian keamanan.
