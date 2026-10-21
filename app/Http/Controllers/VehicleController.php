<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class VehicleController extends Controller {
    public function index(Request $request) { $vehicles = $request->user()->vehicles()->with(['orders'=>fn($q)=>$q->where('status','completed')->with(['items','payment'])->latest()])->latest()->get(); return view('vehicles.index',compact('vehicles')); }
    public function store(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:120','brand'=>'nullable|string|max:80','model'=>'nullable|string|max:80','plate_number'=>'nullable|string|max:20','year'=>'nullable|integer|min:1950|max:'.(now()->year+1),'odometer_km'=>'nullable|integer|min:0']);
        if (!empty($data['plate_number']) && $request->user()->vehicles()->where('plate_number',$data['plate_number'])->exists()) return back()->withErrors(['plate_number'=>'Nomor polisi sudah terdaftar pada akun ini.'])->withInput();
        $request->user()->vehicles()->create($data); return back()->with('success','Kendaraan berhasil ditambahkan.');
    }
}
