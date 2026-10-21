<?php
namespace App\Http\Controllers;
use App\Models\Sparepart;
use Illuminate\Http\Request;
class SparepartController extends Controller {
    public function index(Request $request) {
        $query = Sparepart::where('is_active', true)->where('stock', '>', 0);
        if ($request->filled('q')) $query->where('name', 'like', '%'.addcslashes($request->string('q')->toString(), '%_').'%');
        if ($request->filled('category')) $query->where('category', $request->string('category')->toString());
        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Sparepart::where('is_active', true)->distinct()->orderBy('category')->pluck('category');
        return view('spareparts.index', compact('products','categories'));
    }
}
