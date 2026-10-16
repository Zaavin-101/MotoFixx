<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['nama_jasa' => 'Servis Ringan', 'kategori' => 'Servis', 'harga' => 85000, 'estimasi_waktu' => '45–60 menit', 'status' => true],
            ['nama_jasa' => 'CVT Performance', 'kategori' => 'CVT', 'harga' => 185000, 'estimasi_waktu' => '90 menit', 'status' => true],
            ['nama_jasa' => 'Full Tune Up', 'kategori' => 'Tune Up', 'harga' => 325000, 'estimasi_waktu' => '2–3 jam', 'status' => true],
            ['nama_jasa' => 'Oli + Checkup', 'kategori' => 'Oli & Pelumas', 'harga' => 105000, 'estimasi_waktu' => '30–45 menit', 'status' => true],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['nama_jasa' => $service['nama_jasa']], $service);
        }

        $parts = [
            ['nama_sparepart' => 'Shell Advance AX7', 'kategori' => 'Oli & Pelumas', 'harga' => 68000, 'stok' => 28, 'gambar' => null],
            ['nama_sparepart' => 'Kampas Rem Ceramic Pro', 'kategori' => 'Kampas Rem', 'harga' => 125000, 'stok' => 14, 'gambar' => null],
            ['nama_sparepart' => 'CVT Belt Racing Series', 'kategori' => 'Belt & Chain', 'harga' => 235000, 'stok' => 8, 'gambar' => null],
            ['nama_sparepart' => 'NGK Iridium IX', 'kategori' => 'Kelistrikan', 'harga' => 115000, 'stok' => 22, 'gambar' => null],
        ];

        foreach ($parts as $part) {
            Sparepart::firstOrCreate(['nama_sparepart' => $part['nama_sparepart']], $part);
        }

    }
}
