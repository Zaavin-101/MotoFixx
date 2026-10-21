<?php
namespace Database\Seeders;
use App\Models\ServicePackage;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::firstOrCreate(['email'=>'admin@motorcare.test'], ['name'=>'MotorCare Admin','phone'=>'080000000001','password'=>'password','role'=>'admin']);
        User::firstOrCreate(['email'=>'mechanic@motorcare.test'], ['name'=>'Mekanik Demo','phone'=>'080000000003','password'=>'password','role'=>'mechanic']);
        User::firstOrCreate(['email'=>'customer@motorcare.test'], ['name'=>'Pelanggan Demo','phone'=>'080000000002','password'=>'password','role'=>'customer']);
        $packages = [
            ['name'=>'IRIT','slug'=>'irit','description'=>'Servis rutin ekonomis untuk motor harian.','price'=>79000,'features'=>['Pemeriksaan rem','Setel rantai dan tekanan ban','Pemeriksaan dasar mesin']],
            ['name'=>'MATIC SMOOTH','slug'=>'matic-smooth','description'=>'Perawatan motor matic agar nyaman dan responsif.','price'=>129000,'features'=>['Pemeriksaan CVT','Pembersihan filter','Pemeriksaan kelistrikan']],
            ['name'=>'INJEKSI PRO','slug'=>'injeksi-pro','description'=>'Pemeriksaan sistem injeksi dan performa.','price'=>169000,'features'=>['Pemeriksaan injeksi','Pemeriksaan throttle body','Uji performa']],
            ['name'=>'SULTAN','slug'=>'sultan','description'=>'Paket premium dengan inspeksi menyeluruh.','price'=>279000,'features'=>['Inspeksi menyeluruh','Prioritas antrean','Rekomendasi perawatan']],
        ];
        foreach ($packages as $p) ServicePackage::firstOrCreate(['slug'=>$p['slug']], $p + ['service_type'=>'both','image_url'=>'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80','is_active'=>true]);
        $parts = [
            ['name'=>'Oli Mesin 10W-40','slug'=>'oli-mesin-10w40','sku'=>'OIL-1040','category'=>'Oli & Fluida','description'=>'Pelumas untuk pemakaian harian.','price'=>85000,'stock'=>30],
            ['name'=>'Busi Iridium','slug'=>'busi-iridium','sku'=>'SPK-IRID','category'=>'Pengapian','description'=>'Pengapian stabil dan responsif.','price'=>65000,'stock'=>24],
            ['name'=>'Kampas Rem Depan','slug'=>'kampas-rem-depan','sku'=>'BRK-FR01','category'=>'Rem','description'=>'Komponen pengereman untuk motor tertentu.','price'=>55000,'stock'=>18],
            ['name'=>'V-Belt Matic','slug'=>'v-belt-matic','sku'=>'CVT-VB01','category'=>'CVT','description'=>'Komponen transmisi CVT motor matic.','price'=>110000,'stock'=>12],
        ];
        foreach ($parts as $p) Sparepart::firstOrCreate(['slug'=>$p['slug']], $p + ['image_url'=>'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=800&q=80','is_active'=>true]);
    }
}
