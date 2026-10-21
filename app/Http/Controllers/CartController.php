<?php
namespace App\Http\Controllers;
use App\Models\ServicePackage;
use App\Models\Sparepart;
use Illuminate\Http\Request;
class CartController extends Controller {
    public function index() {
        $cart = session('cart', []); $items = []; $subtotal = 0; $clean = [];
        foreach ($cart as $key => $quantity) {
            if (!preg_match('/^(package|sparepart):(\d+)$/', (string)$key, $m)) continue;
            $type = $m[1]; $id = (int)$m[2]; $quantity = max(1, min(20, (int)$quantity));
            $item = $type === 'package' ? ServicePackage::find($id) : Sparepart::find($id);
            if (!$item || !$item->is_active || ($type === 'sparepart' && $item->stock < 1)) continue;
            $quantity = $type === 'sparepart' ? min($quantity, $item->stock) : $quantity;
            $line = (int)$item->price * $quantity; $subtotal += $line; $clean[$key] = $quantity;
            $items[] = ['key'=>$key,'type'=>$type,'id'=>$id,'name'=>$item->name,'price'=>(int)$item->price,'quantity'=>$quantity,'line'=>$line,'stock'=>$type === 'sparepart' ? $item->stock : null];
        }
        session(['cart'=>$clean]);
        return view('cart.index', compact('items','subtotal'));
    }
    public function add(Request $request) {
        $data = $request->validate(['type'=>'required|in:package,sparepart','id'=>'required|integer|min:1','quantity'=>'nullable|integer|min:1|max:20']);
        $item = $data['type'] === 'package' ? ServicePackage::where('is_active',true)->findOrFail($data['id']) : Sparepart::where('is_active',true)->findOrFail($data['id']);
        $key = $data['type'].':'.$item->id; $cart = session('cart', []); $newQuantity = ($cart[$key] ?? 0) + ($data['quantity'] ?? 1);
        if ($data['type'] === 'sparepart' && $newQuantity > $item->stock) return back()->withErrors(['cart'=>'Jumlah melebihi stok tersedia.']);
        $cart[$key] = min(20, $newQuantity); session(['cart'=>$cart]);
        return redirect()->route('cart.index')->with('success', $item->name.' ditambahkan ke keranjang.');
    }
    public function update(Request $request) {
        $data = $request->validate(['items'=>'required|array','items.*'=>'integer|min:0|max:20']); $cart = session('cart', []);
        foreach ($data['items'] as $key => $quantity) {
            if (!array_key_exists($key, $cart) || !preg_match('/^(package|sparepart):\d+$/', (string)$key)) continue;
            if ($quantity === 0) { unset($cart[$key]); continue; }
            if (str_starts_with($key, 'sparepart:')) { $part = Sparepart::find((int)substr($key, 10)); if (!$part || $quantity > $part->stock) return back()->withErrors(['cart'=>'Jumlah sparepart melebihi stok tersedia.']); }
            $cart[$key] = $quantity;
        }
        session(['cart'=>$cart]); return redirect()->route('cart.index')->with('success','Keranjang diperbarui.');
    }
    public function remove(Request $request) {
        $data = $request->validate(['key'=>'required|string|max:60']); $cart = session('cart', []); unset($cart[$data['key']]); session(['cart'=>$cart]);
        return redirect()->route('cart.index')->with('success','Item dihapus dari keranjang.');
    }
}
