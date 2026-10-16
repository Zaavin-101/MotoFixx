<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama_sparepart'=>'Oli Mesin Motul Scooter Expert','kategori'=>'Oli & Pelumas','harga'=>95000,'stok'=>18],
            ['nama_sparepart'=>'Oli Mesin Yamalube Super Matic','kategori'=>'Oli & Pelumas','harga'=>58000,'stok'=>25],
            ['nama_sparepart'=>'Oli Mesin AHM MPX 2','kategori'=>'Oli & Pelumas','harga'=>52000,'stok'=>30],
            ['nama_sparepart'=>'Kampas Rem Depan Honda Vario','kategori'=>'Kampas Rem','harga'=>78000,'stok'=>12],
            ['nama_sparepart'=>'Kampas Rem Belakang Yamaha NMAX','kategori'=>'Kampas Rem','harga'=>92000,'stok'=>10],
            ['nama_sparepart'=>'Busi NGK CPR9EA-9','kategori'=>'Kelistrikan','harga'=>28000,'stok'=>35],
            ['nama_sparepart'=>'Busi Iridium Universal','kategori'=>'Kelistrikan','harga'=>125000,'stok'=>9],
            ['nama_sparepart'=>'Aki MF GTZ5S','kategori'=>'Kelistrikan','harga'=>245000,'stok'=>8],
            ['nama_sparepart'=>'Aki MF GTZ6V','kategori'=>'Kelistrikan','harga'=>315000,'stok'=>7],
            ['nama_sparepart'=>'V-Belt Honda BeAT','kategori'=>'Belt & Chain','harga'=>145000,'stok'=>11],
            ['nama_sparepart'=>'Roller CVT 12 gram','kategori'=>'Belt & Chain','harga'=>65000,'stok'=>16],
            ['nama_sparepart'=>'Filter Udara Honda Vario','kategori'=>'Filter','harga'=>42000,'stok'=>14],
            ['nama_sparepart'=>'Filter Udara Yamaha NMAX','kategori'=>'Filter','harga'=>68000,'stok'=>10],
            ['nama_sparepart'=>'Ban Tubeless 80/90-14','kategori'=>'Ban & Velg','harga'=>285000,'stok'=>6],
            ['nama_sparepart'=>'Ban Tubeless 90/90-14','kategori'=>'Ban & Velg','harga'=>325000,'stok'=>6],
            ['nama_sparepart'=>'Lampu LED Headlamp Motor','kategori'=>'Lampu & Aksesori','harga'=>110000,'stok'=>13],
            ['nama_sparepart'=>'Kabel Rem Universal','kategori'=>'Kabel & Kontrol','harga'=>35000,'stok'=>20],
            ['nama_sparepart'=>'Grip Stang Anti Slip','kategori'=>'Lampu & Aksesori','harga'=>45000,'stok'=>17],
            ['nama_sparepart'=>'Cairan Pembersih Injector','kategori'=>'Perawatan','harga'=>55000,'stok'=>12],
            ['nama_sparepart'=>'Coolant Radiator Motor','kategori'=>'Oli & Pelumas','harga'=>38000,'stok'=>20],
        ];

        foreach ($items as $item) {
            Sparepart::firstOrCreate(['nama_sparepart' => $item['nama_sparepart']], $item + ['gambar' => null]);
        }
    }
}
