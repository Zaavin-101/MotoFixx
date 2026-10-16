<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Tracking {{ $booking->kode_booking }} — Motofix</title><script src="https://cdn.tailwindcss.com"></script><script>tailwind.config={theme:{extend:{colors:{brand:'#FF6B00',ink:'#0B0B0C',panel:'#1A1A1E'}}}}</script><script>
window.motofixReverb = {
    key: @json(config('broadcasting.connections.reverb.key')),
    host: @json(config('broadcasting.connections.reverb.options.host')),
    port: @json(config('broadcasting.connections.reverb.options.port', 8080)),
    scheme: @json(config('broadcasting.connections.reverb.options.scheme', 'http'))
};
</script>@vite(['resources/js/app.js','resources/js/motofix-tracking.js'])</head>
<body class="min-h-screen bg-ink p-5 text-white md:p-10">
<main class="mx-auto max-w-3xl">
<div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('tracking.index') }}" class="text-sm text-zinc-400">← Kembali ke booking</a><a href="{{ route('booking.invoice',$booking) }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-bold text-black">Lihat / Cetak Invoice</a></div>
<div class="mt-5 rounded-3xl border border-white/10 bg-panel p-6 md:p-8">
<p class="text-xs font-bold tracking-[.2em] text-brand">LIVE TRACKING</p><h1 class="mt-2 text-3xl font-black">{{ $booking->kode_booking }}</h1>
<p class="mt-2 text-sm text-zinc-400">{{ $booking->merek_motor }} {{ $booking->tipe_motor }} · {{ $booking->nomor_plat }}</p>
<div class="mt-6 rounded-2xl border border-white/10 bg-black/20 p-5"><p class="text-xs text-zinc-500">STATUS SEKARANG</p><p id="live-status" class="mt-2 text-2xl font-black text-brand">{{ ucfirst(str_replace('_',' ',$booking->status_pengerjaan)) }}</p><p id="live-note" class="mt-2 text-sm text-zinc-400">Menunggu update dari tim bengkel…</p><p class="mt-3 text-xs text-zinc-500">Koneksi: <span id="connection-status">menghubungkan…</span></p></div>
<div class="mt-7"><h2 class="font-bold">Riwayat pengerjaan</h2><ol id="status-history" class="mt-4 space-y-3">
@forelse($booking->statusHistories as $history)
<li class="flex gap-3 rounded-xl border border-white/10 p-4"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span><div><p class="font-semibold">{{ ucfirst(str_replace('_',' ',$history->status)) }}</p><p class="mt-1 text-sm text-zinc-400">{{ $history->keterangan ?: 'Status diperbarui.' }}</p><p class="mt-1 text-xs text-zinc-500">{{ $history->created_at->format('d M Y H:i') }}</p></div></li>
@empty <li class="text-sm text-zinc-500">Riwayat belum tersedia.</li>@endforelse
</ol></div>
</div>
</main>
<script>
window.motofixTracking = { bookingId: @json($booking->id), userId: @json(auth()->id()), statusUrl: @json(route('tracking.status', $booking)) };
</script>
</body></html>
