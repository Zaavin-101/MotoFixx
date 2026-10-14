<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Service,ServicePackage,Sparepart,Article};
class HomeController extends Controller { public function index(){return response()->json(['services'=>Service::where('is_active',1)->get(),'packages'=>ServicePackage::where('is_active',1)->get(),'spareparts'=>Sparepart::where('is_active',1)->latest()->take(8)->get(),'articles'=>Article::where('is_published',1)->latest('published_at')->take(6)->get()]);} }
