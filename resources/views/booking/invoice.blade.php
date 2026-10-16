<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $booking->kode_booking }} — MOTOFIX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: #111827 !important; }
            .no-print { display: none !important; }
            .invoice-paper { box-shadow: none !important; border: 0 !important; margin: 0 !important; max-width: none !important; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#0b0b0c] px-4 py-8 text-white sm:px-6">
    <main class="mx-auto max-w-3xl">
        <div class="no-print mb-5 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/15 px-4 py-2 text-sm hover:border-orange-500">← Dashboard</a>
            <div class="flex gap-2">
                <a href="{{ route('tracking.show', $booking) }}" class="rounded-xl border border-white/15 px-4 py-2 text-sm hover:border-orange-500">Lihat tracking</a>
                <button onclick="window.print()" class="rounded-xl bg-orange-500 px-5 py-2 font-bold text-black hover:bg-orange-400">Cetak Invoice</button>
            </div>
        </div>
        @if(session('success'))<div class="no-print mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 text-sm text-emerald-200">{{ session('success') }}</div>@endif
        <article class="invoice-paper mx-auto rounded-3xl bg-white p-6 text-slate-900 shadow-2xl sm:p-10">
            <header class="flex flex-wrap items-start justify-between gap-5 border-b-2 border-dashed border-slate-200 pb-6">
                <div><p class="text-xs font-black tracking-[.25em] text-orange-600">BENGKEL ORANGE</p><h1 class="mt-2 text-4xl font-black tracking-tight">MOTOFIX</h1><p class="mt-2 text-sm text-slate-500">Invoice layanan servis motor</p></div>
                <div class="min-w-40 text-left sm:text-right"><p class="text-xs font-bold uppercase tracking-widest text-slate-400">Nomor invoice</p><p class="mt-1 text-lg font-black">{{ $booking->kode_booking }}</p><p class="mt-2 text-sm text-slate-500">Dibuat {{ optional($booking->created_at)->format('d M Y, H:i') }}</p></div>
            </header>
            <section class="grid gap-6 border-b border-slate-200 py-6 sm:grid-cols-2">
                <div><p class="text-xs font-bold uppercase tracking-widest text-slate-400">Informasi pelanggan</p><p class="mt-2 font-bold">{{ $booking->nama_pelanggan ?: $booking->user?->name ?: 'Pelanggan' }}</p><p class="mt-1 text-sm text-slate-600">{{ $booking->merek_motor }} {{ $booking->tipe_motor }}</p><p class="text-sm text-slate-600">Nomor polisi: {{ $booking->nomor_plat }}</p></div>
                <div><p class="text-xs font-bold uppercase tracking-widest text-slate-400">Jadwal servis</p><p class="mt-2 font-bold">{{ optional($booking->tanggal)->format('d M Y') ?? $booking->tanggal }}</p><p class="mt-1 text-sm text-slate-600">Pukul {{ substr((string) $booking->jam, 0, 5) }} WIB</p><p class="text-sm text-slate-600">Mekanik: {{ $booking->mekanik_name }}</p></div>
            </section>
            <section class="py-6">
                <div class="mb-4 grid grid-cols-[1fr_auto] gap-4 border-b border-slate-200 pb-3 text-xs font-bold uppercase tracking-widest text-slate-400"><span>Deskripsi layanan</span><span>Jumlah</span></div>
                <div class="grid grid-cols-[1fr_auto] gap-4"><div><p class="font-bold">{{ $booking->service?->nama_jasa ?? 'Layanan servis' }}</p><p class="mt-1 text-sm text-slate-500">{{ $booking->service?->kategori ?? 'Servis motor' }}{{ $booking->service?->estimasi_waktu ? ' · '.$booking->service->estimasi_waktu : '' }}</p><p class="mt-3 text-sm text-slate-600"><span class="font-semibold">Keluhan:</span> {{ $booking->keluhan }}</p></div><p class="font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p></div>
            </section>
            <section class="border-t-2 border-dashed border-slate-200 pt-5">
                <div class="flex items-start justify-between gap-4"><div><p class="text-sm font-bold">Total tagihan</p><p class="mt-2 text-xs text-slate-500">Metode pembayaran: {{ strtoupper(str_replace('_', ' ', $booking->payment_method ?: 'tunai')) }}</p><p class="mt-1 text-xs text-slate-500">Status pembayaran: {{ $booking->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM DIBAYAR' }}</p></div><p class="text-2xl font-black text-orange-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p></div>
                <div class="mt-7 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600"><p class="font-bold text-slate-800">Terima kasih telah memilih MOTOFIX.</p><p>Invoice ini merupakan ringkasan booking servis. Pembayaran digital tidak diproses otomatis oleh website; silakan ikuti konfirmasi dari petugas bengkel.</p></div>
            </section>
            <footer class="mt-6 border-t border-slate-100 pt-4 text-center text-xs text-slate-400">BENGKEL ORANGE · MOTOFIX · {{ now()->format('Y') }}</footer>
        </article>
        <p class="no-print mt-4 text-center text-xs text-zinc-500">Pilih “Cetak Invoice” untuk mencetak atau menyimpan invoice sebagai PDF.</p>
    </main>
</body>
</html>
