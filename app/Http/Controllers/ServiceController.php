<?php
namespace App\Http\Controllers;
use App\Models\ServicePackage;
class ServiceController extends Controller {
    public function home() { $packages = ServicePackage::where('is_active', true)->orderBy('price')->limit(4)->get(); return view('home', compact('packages')); }
    public function index() { $packages = ServicePackage::where('is_active', true)->orderBy('price')->get(); return view('services.index', compact('packages')); }
}
