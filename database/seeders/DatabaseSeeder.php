<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@motorcare.test'], ['name' => 'MotorCare Admin', 'phone' => '080000000001', 'password' => 'password', 'role' => 'admin']);
        User::firstOrCreate(['email' => 'mechanic@motorcare.test'], ['name' => 'Mekanik Demo', 'phone' => '080000000003', 'password' => 'password', 'role' => 'mechanic']);
        User::firstOrCreate(['email' => 'customer@motorcare.test'], ['name' => 'Pelanggan Demo', 'phone' => '080000000002', 'password' => 'password', 'role' => 'customer']);

        $packages = [
            [
                'name' => 'IRIT',
                'slug' => 'irit',
                'description' => 'Servis rutin ekonomis untuk motor harian.',
                'price' => 79000,
                'features' => ['Pemeriksaan rem', 'Setel rantai dan tekanan ban', 'Pemeriksaan dasar mesin'],
                'image_url' => 'c:\Users\ADVAN\AppData\Local\Packages\Microsoft.ScreenSketch_8wekyb3d8bbwe\TempState\Snips\Screenshot 2026-10-22 005350.png',
            ],
            [
                'name' => 'MATIC SMOOTH',
                'slug' => 'matic-smooth',
                'description' => 'Perawatan motor matic agar nyaman dan responsif.',
                'price' => 129000,
                'features' => ['Pemeriksaan CVT', 'Pembersihan filter', 'Pemeriksaan kelistrikan'],
                'image_url' => 'https://images.unsplash.com/photo-1609630875172-2ec552cc937a?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'INJEKSI PRO',
                'slug' => 'injeksi-pro',
                'description' => 'Pemeriksaan sistem injeksi dan performa.',
                'price' => 169000,
                'features' => ['Pemeriksaan injeksi', 'Pemeriksaan throttle body', 'Uji performa'],
                'image_url' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'SULTAN',
                'slug' => 'sultan',
                'description' => 'Paket premium dengan inspeksi menyeluruh.',
                'price' => 279000,
                'features' => ['Inspeksi menyeluruh', 'Prioritas antrean', 'Rekomendasi perawatan'],
                'image_url' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80',
            ],
        ];
        foreach ($packages as $p) {
            ServicePackage::firstOrCreate(['slug' => $p['slug']], $p + ['service_type' => 'both', 'is_active' => true]);
        }

        $parts = [
            [
                'name' => 'Oli Mesin shell',
                'slug' => 'oli-mesin-shell',
                'sku' => 'OIL-1040',
                'category' => 'Oli & Fluida',
                'description' => 'Pelumas untuk pemakaian harian.',
                'price' => 85000,
                'stock' => 30,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSMriLBsGqUpVX1gJoMbhIgnqlPu7Jo_CKC8aKnKXLxA&s=10',
            ],
            [
                'name' => 'Busi Iridium',
                'slug' => 'busi-iridium',
                'sku' => 'SPK-IRID',
                'category' => 'Pengapian',
                'description' => 'Pengapian stabil dan responsif.',
                'price' => 65000,
                'stock' => 24,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3XJZdEad88T2Zv9SUfmWyo7kqSVMaKKSw7OAO-ZY0NQ&s=10',
            ],
            [
                'name' => 'Kampas Rem Depan',
                'slug' => 'kampas-rem-depan',
                'sku' => 'BRK-FR01',
                'category' => 'Rem',
                'description' => 'Komponen pengereman untuk motor tertentu.',
                'price' => 55000,
                'stock' => 18,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR82JCj68LBLdy9APqUvtRMaD_n2wETeuhQegx4yoMWIg&s=10',
            ],
            [
                'name' => 'V-Belt Matic',
                'slug' => 'v-belt-matic',
                'sku' => 'CVT-VB01',
                'category' => 'CVT',
                'description' => 'Komponen transmisi CVT motor matic.',
                'price' => 110000,
                'stock' => 12,
                'image_url' => 'https://astraotoshop.com/_next/image?url=https%3A%2F%2Fastraotoshop.com%2Fasset%2Farticle-aop%2FMengenal%20V-Belt%20Aspira%20-%20Sabuk%20Penggerak%20Mesin%20Motor_20251218.webp&w=828&q=75',
            ],
        ];
        foreach ($parts as $p) {
            Sparepart::firstOrCreate(['slug' => $p['slug']], $p + ['is_active' => true]);
        }
    }
}
