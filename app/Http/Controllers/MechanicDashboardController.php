<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class MechanicDashboardController extends Controller {
    public function index(Request $request) { $orders = $request->user()->assignedOrders()->with(['user','vehicle','items','payment'])->latest()->paginate(15); return view('mechanic.dashboard',compact('orders')); }
    public function updateStatus(Request $request, Order $order) {
        abort_unless($order->mechanic_id === $request->user()->id,403);
        $data = $request->validate(['status'=>'required|in:mechanic_on_the_way,in_service,completed','mechanic_notes'=>'nullable|string|max:1500']);
        $order->update($data); return back()->with('success','Status pekerjaan diperbarui.');
    }
}
