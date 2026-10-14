<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User,MotorBrand,MotorModel,Mechanic,ServiceCategory,Service,ServicePackage,SparepartCategory,Sparepart,Article};
class DatabaseSeeder extends Seeder { public function run(): void {
 User::updateOrCreate(['email'=>'admin@motocare.test'],['name'=>'MotoCare Admin','password'=>'password','role'=>'admin']);
 User::updateOrCreate(['email'=>'customer@motocare.test'],['name'=>'Customer MotoCare','password'=>'password','role'=>'customer']);
 foreach(['Honda','Yamaha','Suzuki','Kawasaki','Vespa'] as $b){$brand=MotorBrand::firstOrCreate(['name'=>$b]);foreach(($b==='Honda'?['Vario 125','Vario 160','Beat','PCX']:['Aerox','NMAX']) as $m) MotorModel::firstOrCreate(['motor_brand_id'=>$brand->id,'name'=>$m]);}
 $cat=ServiceCategory::firstOrCreate(['name'=>'Service Berkala']);$svc=Service::firstOrCreate(['name'=>'Service Motor Lengkap'],['service_category_id'=>$cat->id,'description'=>'Pemeriksaan dan perawatan menyeluruh','price'=>150000,'duration_minutes'=>90,'is_active'=>1]);ServicePackage::firstOrCreate(['name'=>'Paket Service Premium'],['service_id'=>$svc->id,'description'=>'Paket lengkap untuk motor harian','price'=>250000,'duration_minutes'=>120,'is_active'=>1]);
 foreach(['Budi - Senior','Andi - Engine','Rizky - CVT'] as $n) Mechanic::firstOrCreate(['name'=>$n],['status'=>'active','specialization'=>'Motor matic']);
 $sc=SparepartCategory::firstOrCreate(['name'=>'Oli & Fluida']);Sparepart::firstOrCreate(['sku'=>'OLI-MC-10W40'],['sparepart_category_id'=>$sc->id,'name'=>'Oli MotoCare 10W-40','description'=>'Oli mesin premium','price'=>85000,'stock'=>50,'is_active'=>1]);
 Article::firstOrCreate(['slug'=>'tips-merawat-motor'],['title'=>'5 Tips Merawat Motor','excerpt'=>'Tips sederhana agar motor tetap prima.','content'=>'Ganti oli berkala, cek rem, ban, aki, dan CVT secara rutin.','published_at'=>now(),'is_published'=>1]);
 }}
