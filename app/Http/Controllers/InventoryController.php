<?php
namespace App\Http\Controllers;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class InventoryController extends Controller {
    public function index() { $products = Sparepart::orderBy('name')->paginate(20); return view('admin.inventory',compact('products')); }
    public function store(Request $request) {
        $data = $request->validate(['name'=>'required|string|max:150','slug'=>'nullable|string|max:170|alpha_dash|unique:spareparts,slug','sku'=>'required|string|max:80|unique:spareparts,sku','category'=>'required|string|max:80','description'=>'nullable|string|max:2000','price'=>'required|integer|min:0','stock'=>'required|integer|min:0','image_url'=>'nullable|url|max:1000']);
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['name']).'-'.Str::lower(Str::random(4)); $data['is_active'] = true; Sparepart::create($data);
        return back()->with('success','Produk ditambahkan.');
    }
    public function update(Request $request, Sparepart $sparepart) {
        $data = $request->validate(['price'=>'required|integer|min:0','stock'=>'required|integer|min:0','is_active'=>'required|boolean']);
        $sparepart->update($data); return back()->with('success','Stok dan harga diperbarui.');
    }
}
