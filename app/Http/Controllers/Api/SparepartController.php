<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
class SparepartController extends Controller { public function index(Request $r){$q=Sparepart::with('category')->where('is_active',1);if($r->search)$q->where(fn($x)=>$x->where('name','like','%'.$r->search.'%')->orWhere('sku','like','%'.$r->search.'%'));return response()->json($q->latest()->paginate(12));} public function show(Sparepart $sparepart){abort_unless($sparepart->is_active,404);return response()->json($sparepart->load('category'));} }
