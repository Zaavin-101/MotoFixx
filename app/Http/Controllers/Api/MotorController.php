<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{CustomerMotor,MotorBrand,MotorModel};
use Illuminate\Http\Request;
class MotorController extends Controller { public function index(Request $r){return response()->json(CustomerMotor::with(['brand','model'])->where('user_id',$r->user()->id)->get());} public function store(Request $r){$d=$r->validate(['motor_brand_id'=>'required|exists:motor_brands,id','motor_model_id'=>'required|exists:motor_models,id','plate_number'=>'required|string|max:20','year'=>'nullable|string|max:4','color'=>'nullable|string|max:30']);$d['user_id']=$r->user()->id;return response()->json(CustomerMotor::create($d)->load(['brand','model']),201);} }
