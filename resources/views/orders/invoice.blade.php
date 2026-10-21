<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $order->id ?? 'MC-2026001' }} · MotorCare Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .print-box { border: 1px solid #cbd5e1 !important; background: white !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-[#0b1120] text-slate-100 min-h-screen py-10 px-4">

    <div class="max-w-3xl mx-auto">
        <!-- Tombol Aksi (Tidak ikut tercetak) -->
        <div class="flex justify-between items-center mb-6 no-print">
            <a href="{{ url()->previous() }}" class="text-sm text-orange-400 hover:underline">← Kembali</a>
            <button onclick="window.print()" class="bg-orange-600 hover:bg-orange-700 text-white font-bold px-5 py-2 rounded-xl shadow-lg transition">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>

        <!-- Kotak Utama Invoice -->
        <div class="print-box bg-[#111b2e] border border-slate-800 rounded-2xl p-8 shadow-2xl">
            <!-- Header Invoice -->
            <div class="flex justify-between items-start border-b border-slate-700 pb-6 mb-6">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-orange-500">MotorCare.</h1>
                    <p class="text-xs text-slate-400 mt-1">Platform Servis & Perawatan Motor Modern</p>
                    <p class="text-xs text-slate-400">Jl. Jend. A. H. Nasution, Medan, Sumatera Utara</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold uppercase tracking-wide text-slate-200">INVOICE</h2>
                    <p class="text-sm font-semibold text-orange-400 mt-1">#MC-{{ str_pad($order->id ?? 1, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Tanggal: {{ $order->created_at ?? date('d M Y') }}</p>
                </div>
            </div>

            <!-- Informasi Pelanggan & Layanan -->
            <div class="grid grid-cols-2 gap-6 text-sm mb-8">
                <div>
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Kepada Yth:</p>
                    <p class="font-bold text-slate-200">{{ $order->user->name ?? 'Pelanggan Setia' }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">{{ $order->user->phone ?? '081234567890' }}</p>
                    <p class="text-slate-400 text-xs">{{ $order->user->email ?? 'pelanggan@motorcare.test' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Status Pembayaran:</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        {{ $order->payment_status ?? 'LUNAS (QRIS / Cash)' }}
                    </span>
                    <p class="text-xs text-slate-400 mt-2">Metode: <span class="font-semibold text-slate-300">{{ $order->payment_method ?? 'QRIS' }}</span></p>
                </div>
            </div>

            <!-- Tabel Rincian Pesanan -->
            <div class="mb-8 overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-700 text-xs text-slate-400 uppercase">
                            <th class="py-3 px-2">Deskripsi Layanan / Item</th>
                            <th class="py-3 px-2 text-center">Tipe</th>
                            <th class="py-3 px-2 text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-300">
                        <tr>
                            <td class="py-3 px-2">
                                <p class="font-bold text-slate-100">Paket Servis / Sparepart</p>
                                <p class="text-xs text-slate-400">{{ $order->package_name ?? 'Paket Servis Rutin & Perawatan Berkala' }}</p>
                            </td>
                            <td class="py-3 px-2 text-center text-xs">
                                <span class="bg-slate-800 px-2 py-1 rounded text-orange-400">Home Service</span>
                            </td>
                            <td class="py-3 px-2 text-right font-semibold text-slate-100">
                                Rp {{ number_format($order->total_amount ?? 129000, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total Pembayaran -->
            <div class="flex justify-end border-t border-slate-700 pt-4 mb-8">
                <div class="w-64 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-400">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->total_amount ?? 129000, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Biaya Layanan</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-orange-400 border-t border-slate-700 pt-2">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($order->total_amount ?? 129000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer / Tanda Tangan -->
            <div class="border-t border-slate-800 pt-6 flex justify-between items-end text-xs text-slate-400">
                <div>
                    <p class="font-semibold text-slate-300">Catatan:</p>
                    <p>Terima kasih telah mempercayakan perawatan kendaraan Anda di MotorCare.</p>
                </div>
                <div class="text-center">
                    <p class="mb-12">Hormat Kami,</p>
                    <p class="font-bold text-slate-200 border-b border-slate-600 pb-1">Admin MotorCare</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>