<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartOrder;
use App\Models\SparepartOrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SparepartShopController extends Controller
{
    private function ensureCustomer(): void { abort_if(auth()->user()->isAdmin(), 403, 'Toko pelanggan tidak tersedia untuk akun admin.'); }

    public function index(Request $request): View
    {
        $this->ensureCustomer();
        $parts = Sparepart::where('stok', '>', 0)->orderBy('kategori')->orderBy('nama_sparepart')->get();
        return view('shop.index', compact('parts'));
    }

    public function checkout(Request $request): RedirectResponse
    {
        $this->ensureCustomer();
        $data = $request->validate([
            'items' => ['required','array'],
            'items.*' => ['nullable','integer','min:0','max:99'],
            'payment_method' => ['required','in:qris,transfer_bank,debit,tunai'],
        ]);
        $items = collect($data['items'])->filter(fn ($qty) => (int)$qty > 0);
        if ($items->isEmpty()) return back()->withErrors(['items' => 'Pilih minimal satu sparepart dengan jumlah lebih dari nol.']);

        $order = DB::transaction(function () use ($items, $data, $request) {
            $lines = []; $total = 0;
            foreach ($items as $partId => $qty) {
                $part = Sparepart::whereKey((int)$partId)->lockForUpdate()->firstOrFail();
                $qty = (int)$qty;
                if ($part->stok < $qty) throw \Illuminate\Validation\ValidationException::withMessages(['items' => "Stok {$part->nama_sparepart} tidak mencukupi. Sisa stok: {$part->stok}."]);
                $subtotal = $part->harga * $qty; $total += $subtotal;
                $lines[] = [$part, $qty, $subtotal];
            }
            do { $code = 'SP-' . strtoupper(Str::random(8)); } while (SparepartOrder::where('kode_pesanan', $code)->exists());
            $order = SparepartOrder::create([
                'user_id' => $request->user()->id, 'kode_pesanan' => $code,
                'payment_method' => $data['payment_method'], 'status_pembayaran' => 'belum_dibayar',
                'status_pesanan' => 'menunggu', 'total_harga' => $total,
            ]);
            foreach ($lines as [$part, $qty, $subtotal]) {
                SparepartOrderItem::create([
                    'sparepart_order_id' => $order->id, 'sparepart_id' => $part->id,
                    'nama_sparepart' => $part->nama_sparepart, 'harga_satuan' => $part->harga,
                    'jumlah' => $qty, 'subtotal' => $subtotal,
                ]);
                $part->decrement('stok', $qty);
            }
            return $order;
        });
        return redirect()->route('spareparts.orders.show', $order)->with('success', 'Pesanan sparepart berhasil dibuat. Pembayaran belum diproses otomatis.');
    }

    public function orders(Request $request): View
    {
        $this->ensureCustomer();
        $orders = $request->user()->sparepartOrders()->with('items')->latest()->get();
        return view('shop.orders', compact('orders'));
    }

    public function showOrder(Request $request, SparepartOrder $order): View
    {
        $this->ensureCustomer();
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');
        return view('shop.show-order', compact('order'));
    }
}
