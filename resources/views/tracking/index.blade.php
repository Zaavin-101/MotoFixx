<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Tracking — Motofix</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{brand:'#FF6B00',ink:'#0B0B0C',panel:'#1A1A1E'}}}}</script></head>
<body class="min-h-screen bg-ink text-white p-5 md:p-10">
<div class="mx-auto max-w-5xl">
    <div class="flex flex-wrap items-center justify-between gap-4"><div><p class="text-xs font-bold tracking-[.2em] text-brand">MOTOFIX SERVICE</p><h1 class="mt-2 text-3xl font-black">Booking & Live Tracking</h1><p class="mt-2 text-sm text-zinc-400">Status akan diperbarui realtime saat tim bengkel mengubah pengerjaan.</p></div><a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 px-4 py-2 text-sm">← Dashboard</a></div>
    @if(session('success'))<p class="mt-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-300">{{ session('success') }}</p>@endif
    <div class="mt-7 grid gap-4">
    @forelse($bookings as $booking)
        <article class="rounded-2xl border border-white/10 bg-panel p-5">
            <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs text-zinc-500">KODE BOOKING</p><h2 class="mt-1 text-xl font-black text-brand">{{ $booking->kode_booking }}</h2><p class="mt-2 text-sm">{{ $booking->merek_motor }} {{ $booking->tipe_motor }} · {{ $booking->nomor_plat }}</p><p class="mt-1 text-xs text-zinc-400">{{ $booking->service?->nama_jasa }} · {{ $booking->mekanik_name }}</p>@if(auth()->user()->isAdmin())<p class="mt-1 text-xs text-zinc-500">Pelanggan: {{ $booking->user?->name ?? $booking->nama_pelanggan }}</p>@endif</div><div class="text-right"><span class="rounded-full bg-orange-500/10 px-3 py-1.5 text-xs font-bold text-orange-300">{{ ucfirst(str_replace('_',' ',$booking->status_pengerjaan)) }}</span><p class="mt-3 text-sm font-bold">Rp {{ number_format($booking->total_harga,0,',','.') }}</p><a href="{{ route('tracking.show',$booking) }}" class="mt-3 inline-block text-sm font-bold text-brand">Lihat tracking →</a></div></div>
            @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.bookings.status',$booking) }}" class="mt-5 grid gap-3 border-t border-white/10 pt-4 md:grid-cols-4">@csrf @method('PATCH')
                <label class="text-xs text-zinc-400">Status servis<select name="status_pengerjaan" class="mt-1 w-full rounded-lg border border-white/10 bg-black/30 p-2 text-sm text-white">@foreach(['antrian','pengecekan','pengerjaan','test_drive','selesai'] as $status)<option value="{{ $status }}" @selected($booking->status_pengerjaan===$status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>@endforeach</select></label>
                <label class="text-xs text-zinc-400">Pembayaran<select name="status_pembayaran" class="mt-1 w-full rounded-lg border border-white/10 bg-black/30 p-2 text-sm text-white"><option value="belum_dibayar" @selected($booking->status_pembayaran==='belum_dibayar')>Belum dibayar</option><option value="lunas" @selected($booking->status_pembayaran==='lunas')>Lunas</option></select></label>
                <label class="text-xs text-zinc-400 md:col-span-2">Keterangan<input name="keterangan" maxlength="255" placeholder="Contoh: penggantian oli selesai" class="mt-1 w-full rounded-lg border border-white/10 bg-black/30 p-2 text-sm text-white"></label>
                <button class="rounded-lg bg-brand px-4 py-2 text-sm font-black text-black md:col-span-4">Simpan status & kirim update realtime</button>
            </form>
            @endif
        </article>
    @empty
        <div class="rounded-2xl border border-white/10 bg-panel p-8 text-center"><h2 class="text-xl font-bold">Belum ada booking</h2><p class="mt-2 text-sm text-zinc-400">Buat booking servis terlebih dahulu dari dashboard.</p><a href="{{ route('dashboard') }}" class="mt-4 inline-block rounded-xl bg-brand px-4 py-2 font-bold text-black">Buat booking</a></div>
    @endforelse
    </div>
</div>
</body></html>
