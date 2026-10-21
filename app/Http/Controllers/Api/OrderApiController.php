<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderApiController extends Controller {
    public function index(Request $request) { return $request->user()->orders()->with(['items','payment','vehicle'])->latest()->paginate(15); }
    public function show(Request $request, Order $order) { abort_unless($order->user_id === $request->user()->id || $request->user()->role === 'admin',403); return $order->load(['items','payment','vehicle','mechanicLocations']); }
}
