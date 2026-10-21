<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminDashboardController extends Controller {
    public function index() {
        $orders = Order::with(['user','mechanic','payment'])->latest()->limit(50)->get();
        $revenue = DB::table('payments')->where('status','paid')->sum('amount');
        $stock = Sparepart::sum('stock'); $pending = Order::whereIn('status',['pending','confirmed'])->count();
        $monthly = collect(range(1,12))->mapWithKeys(fn ($month) => [$month => 0]);
        $paidPayments = DB::table('payments')->where('status','paid')->whereBetween('updated_at',[now()->startOfYear(),now()->endOfYear()])->get(['updated_at','amount']);
        foreach ($paidPayments as $payment) { $month = (int) date('n', strtotime($payment->updated_at)); $monthly[$month] += (int) $payment->amount; }
        $channels = Order::select('service_type',DB::raw('count(*) as total'))->groupBy('service_type')->pluck('total','service_type');
        $mechanics = User::where('role','mechanic')->orderBy('name')->get();
        return view('admin.dashboard',compact('orders','revenue','stock','pending','monthly','channels','mechanics'));
    }
    public function updateStatus(Request $request, Order $order) {
        $data = $request->validate(['status'=>'required|in:pending,confirmed,mechanic_on_the_way,in_service,completed,cancelled','mechanic_id'=>'nullable|integer|exists:users,id','mechanic_notes'=>'nullable|string|max:1500']);
        if (!empty($data['mechanic_id']) && !User::where('id',$data['mechanic_id'])->where('role','mechanic')->exists()) return back()->withErrors(['mechanic_id'=>'Akun yang dipilih bukan mekanik.']);
        if ($order->status === 'cancelled' && $data['status'] !== 'cancelled') return back()->withErrors(['status'=>'Pesanan yang dibatalkan tidak dapat dibuka kembali. Buat pesanan baru.']);
        DB::transaction(function () use ($order, $data) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($data['status'] === 'cancelled' && $locked->status !== 'cancelled') {
                foreach ($locked->items()->whereNotNull('sparepart_id')->get() as $item) {
                    Sparepart::whereKey($item->sparepart_id)->increment('stock', $item->quantity);
                }
            }
            $locked->update(['status'=>$data['status'],'mechanic_id'=>$data['mechanic_id'] ?? $locked->mechanic_id,'mechanic_notes'=>$data['mechanic_notes'] ?? $locked->mechanic_notes]);
        });
        return back()->with('success','Pesanan dan penugasan diperbarui. Jika dibatalkan, stok sparepart dikembalikan. Pembayaran yang sudah berhasil perlu ditangani sebagai refund terpisah.');
    }
    public function markCashPaid(Request $request, Order $order) {
        $payment = $order->payment;
        abort_unless($payment && $payment->provider === 'cash' && $payment->status !== 'paid', 422, 'Hanya pembayaran tunai yang belum lunas yang bisa ditandai lunas di sini.');
        $payment->update(['status'=>'paid','raw_response'=>['marked_by_admin'=>$request->user()->id,'marked_at'=>now()->toISOString()]]);
        return back()->with('success','Pembayaran tunai ditandai lunas.');
    }
}
