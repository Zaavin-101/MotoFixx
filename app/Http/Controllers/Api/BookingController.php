<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Booking,CustomerMotor,Mechanic,ServicePackage,ServiceProgress,MotorBrand,MotorModel};
use Illuminate\Http\Request;
class BookingController extends Controller {
 public function data(Request $r){$u=$r->user();return response()->json(['motors'=>CustomerMotor::with(['brand','model'])->where('user_id',$u->id)->get(),'brands'=>MotorBrand::with('models')->get(),'models'=>MotorModel::all(),'packages'=>ServicePackage::where('is_active',1)->get(),'mechanics'=>Mechanic::where('status','active')->get()]);}
 public function index(Request $r){return response()->json(Booking::with(['customerMotor.brand','customerMotor.model','mechanic','servicePackage','progress'])->where('user_id',$r->user()->id)->latest()->get());}
 public function store(Request $r){$d=$r->validate(['customer_motor_id'=>'required|exists:customer_motors,id','mechanic_id'=>'nullable|exists:mechanics,id','service_package_id'=>'required|exists:service_packages,id','booking_date'=>'required|date','booking_time'=>'required','complaint'=>'required|string|max:2000']);$motor=CustomerMotor::where('id',$d['customer_motor_id'])->where('user_id',$r->user()->id)->firstOrFail();$p=ServicePackage::findOrFail($d['service_package_id']);$d['user_id']=$r->user()->id;$d['estimated_price']=$p->price;$d['status']='pending';$b=Booking::create($d);ServiceProgress::create(['booking_id'=>$b->id,'status'=>'pending','title'=>'Booking diterima','description'=>'Menunggu konfirmasi bengkel.','progress_percent'=>10]);return response()->json(['message'=>'Booking berhasil dibuat','booking'=>$b->load(['customerMotor.brand','customerMotor.model','servicePackage','progress'])],201);}
 public function show(Request $r,Booking $booking){abort_unless($booking->user_id===$r->user()->id,403);return response()->json($booking->load(['customerMotor.brand','customerMotor.model','mechanic','servicePackage','progress']));}
 public function tracking(Request $r,Booking $booking){abort_unless($booking->user_id===$r->user()->id,403);return response()->json(['booking'=>$booking->load(['customerMotor.brand','customerMotor.model','mechanic','servicePackage']),'progress'=>$booking->progress()->latest()->get()]);}
}
