<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ServicePackage;
use App\Models\Sparepart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CheckoutController extends Controller {
    public function index(Request $request) {
        $cart = session('cart', []); $items = []; $subtotal = 0;
        foreach ($cart as $key => $quantity) {
            if (!preg_match('/^(package|sparepart):(\d+)$/', (string)$key, $m)) continue;
            $item = $m[1] === 'package' ? ServicePackage::where('is_active',true)->find($m[2]) : Sparepart::where('is_active',true)->find($m[2]);
            if (!$item) continue; $quantity = max(1,(int)$quantity); $line = (int)$item->price * $quantity; $subtotal += $line;
            $items[] = ['name'=>$item->name,'quantity'=>$quantity,'price'=>(int)$item->price,'line'=>$line];
        }
        if (!$items) return redirect()->route('cart.index')->withErrors(['cart'=>'Keranjang kosong atau item tidak lagi tersedia.']);
        $vehicles = $request->user()->vehicles()->orderBy('created_at','desc')->get();
        return view('checkout.index', compact('items','subtotal','vehicles'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'service_type'=>'required|in:home,in_shop', 'address'=>'required_if:service_type,home|nullable|string|max:1000',
            'latitude'=>'nullable|numeric|between:-90,90','longitude'=>'nullable|numeric|between:-180,180',
            'payment_method'=>'required|in:midtrans,cod','vehicle_id'=>'nullable|integer','vehicle_name'=>'required_without:vehicle_id|nullable|string|max:120',
            'brand'=>'nullable|string|max:80','model'=>'nullable|string|max:80','plate_number'=>'nullable|string|max:20',
            'odometer_km'=>'nullable|integer|min:0','notes'=>'nullable|string|max:1000'
        ]);
        $cart = session('cart', []); if (!$cart) return back()->withErrors(['cart'=>'Keranjang masih kosong.'])->withInput();
        try {
            $order = DB::transaction(function () use ($request, $data, $cart) {
            $prepared = []; $subtotal = 0;
            foreach ($cart as $key => $quantity) {
                if (!preg_match('/^(package|sparepart):(\d+)$/', (string)$key, $m)) continue;
                $type = $m[1]; $quantity = max(1,(int)$quantity); $id = (int)$m[2];
                $item = $type === 'package' ? ServicePackage::where('is_active',true)->lockForUpdate()->find($id) : Sparepart::where('is_active',true)->lockForUpdate()->find($id);
                if (!$item) continue;
                if ($type === 'sparepart' && $item->stock < $quantity) throw new \DomainException('Stok '.$item->name.' tidak mencukupi.');
                $line = (int)$item->price * $quantity; $subtotal += $line; $prepared[] = [$type,$item,$quantity,$line];
            }
            if (!$prepared) throw new \DomainException('Tidak ada item valid dalam keranjang.');
            if (!empty($data['vehicle_id'])) {
                $vehicle = $request->user()->vehicles()->findOrFail($data['vehicle_id']);
            } else {
                $vehicle = Vehicle::create(['user_id'=>$request->user()->id,'name'=>$data['vehicle_name'],'brand'=>$data['brand'] ?? null,'model'=>$data['model'] ?? null,'plate_number'=>$data['plate_number'] ?? null,'odometer_km'=>$data['odometer_km'] ?? null]);
            }
            $visitFee = $data['service_type'] === 'home' ? 25000 : 0;
            $order = Order::create(['user_id'=>$request->user()->id,'vehicle_id'=>$vehicle->id,'order_number'=>'MC-'.now()->format('ymd').'-'.Str::upper(Str::random(7)),'status'=>'pending','service_type'=>$data['service_type'],'address'=>$data['address'] ?? null,'latitude'=>$data['latitude'] ?? null,'longitude'=>$data['longitude'] ?? null,'subtotal'=>$subtotal,'visit_fee'=>$visitFee,'total'=>$subtotal+$visitFee,'payment_method'=>$data['payment_method'],'notes'=>$data['notes'] ?? null]);
            foreach ($prepared as [$type,$item,$quantity,$line]) {
                OrderItem::create(['order_id'=>$order->id,'package_id'=>$type === 'package' ? $item->id : null,'sparepart_id'=>$type === 'sparepart' ? $item->id : null,'item_name'=>$item->name,'quantity'=>$quantity,'unit_price'=>(int)$item->price,'line_total'=>$line]);
                if ($type === 'sparepart') $item->decrement('stock',$quantity);
            }
            Payment::create(['order_id'=>$order->id,'provider'=>$data['payment_method'] === 'cod' ? 'cash' : 'midtrans','method'=>$data['payment_method'] === 'cod' ? 'cash_on_delivery' : 'snap','status'=>$data['payment_method'] === 'cod' ? 'pending' : 'created','amount'=>$order->total]);
                return $order;
            });
        } catch (\DomainException $e) {
            return back()->withErrors(['cart'=>$e->getMessage()])->withInput();
        }
        session()->forget('cart');
        try { if ($data['payment_method'] === 'midtrans') $this->createSnapTransaction($order, $request); }
        catch (\Throwable $e) { report($e); return redirect()->route('orders.show',$order)->with('warning','Pesanan tercatat, tetapi token pembayaran belum dibuat. Periksa konfigurasi Midtrans Sandbox di .env, lalu coba buat token kembali.'); }
        return redirect()->route('orders.show',$order)->with('success','Pesanan berhasil dibuat.');
    }
    public function retryPayment(Request $request, Order $order) {
        abort_unless($order->user_id === $request->user()->id || $request->user()->role === 'admin',403);
        abort_unless($order->payment_method === 'midtrans' && $order->payment && $order->payment->status !== 'paid', 422);
        try { $this->createSnapTransaction($order, $request); }
        catch (\Throwable $e) { report($e); return back()->with('warning','Token pembayaran gagal dibuat. Pastikan konfigurasi Midtrans Sandbox benar.'); }
        return back()->with('success','Token pembayaran berhasil dibuat.');
    }
    private function createSnapTransaction(Order $order, Request $request): void {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diatur.');
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.production', false);
        \Midtrans\Config::$isSanitized = true; \Midtrans\Config::$is3ds = true;
        $response = \Midtrans\Snap::createTransaction(['transaction_details'=>['order_id'=>$order->order_number,'gross_amount'=>$order->total],'customer_details'=>['first_name'=>$order->user->name,'email'=>$order->user->email,'phone'=>$order->user->phone ?? '']]);
        $order->payment()->update(['snap_token'=>$response->token ?? null,'raw_response'=>(array)$response]);
    }
    public function orders(Request $request) { $orders = $request->user()->orders()->with('items','payment','vehicle')->latest()->paginate(10); return view('orders.index', compact('orders')); }
    public function show(Request $request, Order $order) {
        abort_unless($order->user_id === $request->user()->id || in_array($request->user()->role,['admin','mechanic'],true),403);
        if ($request->user()->role === 'mechanic') abort_unless($order->mechanic_id === $request->user()->id,403);
        $order->load('items','payment','mechanicLocations','vehicle','mechanic'); return view('orders.show', compact('order'));
    }
}
