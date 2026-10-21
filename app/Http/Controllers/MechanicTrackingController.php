<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class MechanicTrackingController extends Controller {
    public function latest(Request $request, Order $order) {
        $this->authorizeOrder($request, $order);
        return response()->json(['customer'=>['latitude'=>$order->latitude,'longitude'=>$order->longitude],'mechanic'=>$order->mechanicLocations()->latest('recorded_at')->first(),'status'=>$order->status]);
    }
    public function update(Request $request, Order $order) {
        abort_unless(in_array($request->user()->role,['admin','mechanic'],true),403);
        if ($request->user()->role === 'mechanic') abort_unless($order->mechanic_id === $request->user()->id,403);
        $data = $request->validate(['latitude'=>'required|numeric|between:-90,90','longitude'=>'required|numeric|between:-180,180']);
        $location = $order->mechanicLocations()->create($data + ['recorded_at'=>now()]);
        return response()->json(['ok'=>true,'location'=>$location]);
    }
    private function authorizeOrder(Request $request, Order $order): void {
        abort_unless($order->user_id === $request->user()->id || $request->user()->role === 'admin' || ($request->user()->role === 'mechanic' && $order->mechanic_id === $request->user()->id),403);
    }
}
