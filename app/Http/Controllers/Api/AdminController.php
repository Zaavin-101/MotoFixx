<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Booking,Sparepart,Service,Mechanic,User,ServiceProgress};
use Illuminate\Http\Request;
class AdminController extends Controller {
 public function dashboard(){return response()->json(['stats'=>['customers'=>User::where('role','customer')->count(),'bookings'=>Booking::count(),'pending_bookings'=>Booking::where('status','pending')->count(),'spareparts'=>Sparepart::count(),'revenue'=>\App\Models\Order::where('status','paid')->sum('total_amount')],'recent_bookings'=>Booking::with(['user','customerMotor','servicePackage'])->latest()->take(10)->get()]);}
 public function bookings(){return response()->json(Booking::with(['user','customerMotor.brand','customerMotor.model','mechanic','servicePackage','progress'])->latest()->paginate(20));}
 public function updateBooking(Request $r,Booking $booking){$d=$r->validate(['status'=>'required|in:pending,confirmed,in_progress,completed,cancelled','mechanic_id'=>'nullable|exists:mechanics,id','progress_percent'=>'nullable|integer|min:0|max:100','progress_title'=>'nullable|string|max:100','progress_description'=>'nullable|string']);$booking->update(array_filter(['status'=>$d['status'],'mechanic_id'=>$d['mechanic_id']??null],fn($v)=>$v!==null));if(isset($d['progress_percent']))ServiceProgress::create(['booking_id'=>$booking->id,'status'=>$d['status'],'title'=>$d['progress_title']??ucwords(str_replace('_',' ',$d['status'])),'description'=>$d['progress_description']??null,'progress_percent'=>$d['progress_percent']]);return response()->json(['message'=>'Booking diperbarui','booking'=>$booking->fresh()->load('progress')]);}
 public function spareparts(){return response()->json(Sparepart::with('category')->latest()->paginate(20));}
 public function storeSparepart(Request $r){$d=$r->validate(['sparepart_category_id'=>'nullable|exists:sparepart_categories,id','name'=>'required','sku'=>'required|unique:spareparts,sku','description'=>'nullable','price'=>'required|numeric|min:0','stock'=>'required|integer|min:0','image'=>'nullable|string']);return response()->json(Sparepart::create($d),201);}
 public function updateSparepart(Request $r,Sparepart $sparepart){$d=$r->validate(['sparepart_category_id'=>'nullable|exists:sparepart_categories,id','name'=>'required','sku'=>'required|unique:spareparts,sku,'.$sparepart->id,'description'=>'nullable','price'=>'required|numeric|min:0','stock'=>'required|integer|min:0','image'=>'nullable|string']);$sparepart->update($d);return response()->json($sparepart);}
 public function deleteSparepart(Sparepart $sparepart){$sparepart->delete();return response()->json(['message'=>'Sparepart dihapus']);}
 public function services(){return response()->json(Service::with('category')->latest()->paginate(20));}
 public function mechanics(){return response()->json(Mechanic::latest()->paginate(20));}
}
