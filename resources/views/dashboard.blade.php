<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ProjectMotofixx — Bengkel Orange</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: '#FF6B00', ink: '#0B0B0C', panel: '#1A1A1E' } } } };
    </script>
    <style>
        body { background:#0B0B0C; color:#f4f4f5; }
        .nav-btn.active { background:rgba(255,107,0,.13); color:#ff7a1a; border:1px solid rgba(255,107,0,.25); }
        .page { animation:fadeIn .22s ease-out; }
        @keyframes fadeIn { from {opacity:0; transform:translateY(5px)} to {opacity:1;transform:translateY(0)} }
        .toast { animation:toastIn .25s ease-out; }
        .toast.hide { animation:toastOut .25s ease-in forwards; }
        @keyframes toastIn { from {opacity:0;transform:translateY(-8px)} to {opacity:1;transform:translateY(0)} }
        @keyframes toastOut { to {opacity:0;transform:translateY(-8px)} }
        .pulse-dot { animation:pulse 1.5s infinite; }
        @keyframes pulse { 50% { box-shadow:0 0 0 7px rgba(255,107,0,.12); opacity:.65; } }
        @media print {
            body { background:white!important; color:#111!important; }
            aside, header, .no-print, .page:not(#page-invoice), #toast { display:none!important; }
            main { padding:0!important; margin:0!important; }
            #page-invoice { display:block!important; }
            .receipt { box-shadow:none!important; border:1px solid #ddd!important; }
        }
    </style>
</head>
<body class="min-h-screen">
<div id="toast" class="fixed right-5 top-5 z-50 hidden max-w-sm rounded-xl border border-emerald-500/30 bg-[#16221b] px-4 py-3 text-sm text-emerald-300 shadow-2xl"></div>

<div class="min-h-screen lg:flex">
    <aside class="no-print flex w-full flex-col border-b border-white/10 bg-[#151518] p-5 lg:fixed lg:inset-y-0 lg:w-64 lg:border-b-0 lg:border-r">
        <div class="mb-8 flex items-center gap-3">
            <div class="grid h-11 w-11 place-items-center rounded-xl bg-brand text-xl font-black text-black">M</div>
            <div><p class="font-black tracking-wide">BENGKEL <span class="text-brand">ORANGE</span></p><p class="text-[10px] tracking-[.25em] text-zinc-500">MOTOFIX SERVICE</p></div>
        </div>
        <p class="mb-3 text-[10px] font-bold uppercase tracking-[.22em] text-zinc-500">Workspace</p>
        <a href="{{ route('tracking.index') }}" class="mb-2 rounded-xl border border-white/10 px-4 py-3 text-sm text-zinc-300 transition hover:border-brand/50 hover:text-brand">◷ &nbsp; Booking & Tracking</a>
        @if(auth()->user()->isAdmin())<a href="{{ route('admin.charts') }}" class="mb-2 rounded-xl border border-white/10 px-4 py-3 text-sm text-zinc-300 transition hover:border-brand/50 hover:text-brand">▥ &nbsp; Statistik & Chart</a>@endif
        <form method="POST" action="{{ route('logout') }}" class="mb-5">@csrf<button class="w-full rounded-xl border border-white/10 px-4 py-3 text-left text-sm text-zinc-400 transition hover:border-red-500/40 hover:text-red-300">↪ &nbsp; Logout</button></form>
        <nav class="flex gap-2 overflow-x-auto lg:flex-col" aria-label="Navigasi utama">
            <button class="nav-btn active rounded-xl border border-transparent px-4 py-3 text-left text-sm transition hover:scale-[1.02]" data-tab="home">⌂ &nbsp; Beranda</button>
            <button class="nav-btn rounded-xl border border-transparent px-4 py-3 text-left text-sm text-zinc-400 transition hover:scale-[1.02]" data-tab="booking">＋ &nbsp; Booking Servis</button>
            <button class="nav-btn rounded-xl border border-transparent px-4 py-3 text-left text-sm text-zinc-400 transition hover:scale-[1.02]" data-tab="tracking">◷ &nbsp; Live Tracking</button>
            <button class="nav-btn rounded-xl border border-transparent px-4 py-3 text-left text-sm text-zinc-400 transition hover:scale-[1.02]" data-tab="parts">▦ &nbsp; Sparepart</button>
            <button class="nav-btn rounded-xl border border-transparent px-4 py-3 text-left text-sm text-zinc-400 transition hover:scale-[1.02]" data-tab="invoice">▤ &nbsp; Nota Digital</button>
        </nav>
        <div class="mt-auto hidden pt-8 lg:block">
            <a href="#" class="text-xs text-zinc-500 hover:text-brand" onclick="event.preventDefault();showToast('Admin Control Room: modul admin dapat ditambahkan berikutnya.')">↗ Admin Control Room</a>
            <div class="mt-4 rounded-2xl border border-white/10 bg-[#1b1b20] p-4">
                <p class="text-xs font-bold">BUTUH BANTUAN?</p><p class="mt-1 text-xs leading-5 text-zinc-500">Hubungi teknisi jika kamu butuh bantuan servis.</p>
                <button onclick="showToast('Tim bantuan akan segera menghubungi kamu.')" class="mt-3 w-full rounded-lg border border-white/10 px-3 py-2 text-xs transition hover:border-brand hover:text-brand">Hubungi teknisi</button>
            </div>
        </div>
    </aside>

    <main class="min-w-0 flex-1 lg:ml-64">
        <header class="no-print sticky top-0 z-20 flex flex-wrap items-center justify-between gap-3 border-b border-white/10 bg-[#0B0B0C]/90 px-5 py-4 backdrop-blur-xl md:px-8">
            <div><p class="text-xs text-zinc-500">Workspace / <span id="crumb" class="text-zinc-300">Beranda</span></p><p class="mt-1 text-sm font-semibold">Dashboard Layanan</p></div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-2 text-[10px] font-bold tracking-wide text-emerald-300">● BUKA HARI INI · 08:00–20:00</span>
                <div class="flex items-center gap-2"><div class="grid h-9 w-9 place-items-center rounded-full bg-zinc-800 text-xs font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div><div><p class="text-xs font-semibold">{{ auth()->user()->name }}</p><p class="text-[9px] tracking-widest text-zinc-500">{{ strtoupper(auth()->user()->role) }}</p></div></div>
            </div>
        </header>

        <div class="p-5 md:p-8">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                    <p class="mb-1 font-bold">Periksa kembali data booking:</p>
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif

            <section id="page-home" class="page">
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-[#24201d] via-[#171719] to-[#121214] p-7 md:p-12">
                    <div class="absolute -right-10 -top-16 h-64 w-64 rounded-full bg-brand/10 blur-3xl"></div>
                    <span class="rounded-full border border-brand/30 bg-brand/10 px-3 py-1.5 text-[10px] font-bold tracking-[.18em] text-orange-300">MOTORCARE • DIGITAL SERVICE</span>
                    <h1 class="relative mt-6 max-w-2xl text-4xl font-black leading-[1.05] tracking-tight md:text-6xl">MOTOR KAMU.<br><span class="text-brand">PERFORMA KAMI.</span></h1>
                    <p class="relative mt-5 max-w-xl text-sm leading-7 text-zinc-400">Perawatan motor lebih mudah, transparan, dan terukur. Booking servis, pantau progres pengerjaan, serta temukan sparepart dalam satu dashboard.</p>
                    <div class="relative mt-7 flex flex-wrap gap-3">
                        <button onclick="switchTab('booking')" class="rounded-xl bg-brand px-5 py-3 text-sm font-bold text-black transition hover:scale-[1.02] hover:bg-orange-400">Booking Servis ↗</button>
                        <button onclick="switchTab('tracking')" class="rounded-xl border border-white/15 px-5 py-3 text-sm font-semibold transition hover:scale-[1.02] hover:border-brand">Lacak progres</button>
                    </div>
                </div>
                <div class="mt-8 flex items-end justify-between"><div><p class="text-xs font-bold uppercase tracking-[.2em] text-brand">Quick Access</p><h2 class="mt-2 text-2xl font-bold">Semua kebutuhan, satu tempat.</h2></div><p class="hidden text-xs text-zinc-500 sm:block">Pilih menu untuk mulai</p></div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <button onclick="switchTab('booking')" class="rounded-2xl border border-white/10 bg-panel p-5 text-left transition hover:scale-[1.02] hover:border-brand/50"><span class="text-2xl text-brand">＋</span><p class="mt-5 font-bold">Booking servis</p><p class="mt-2 text-xs leading-5 text-zinc-500">Pilih paket dan jadwalkan kedatangan.</p><p class="mt-5 text-xs text-brand">Buat booking →</p></button>
                    <button onclick="switchTab('tracking')" class="rounded-2xl border border-white/10 bg-panel p-5 text-left transition hover:scale-[1.02] hover:border-brand/50"><span class="text-2xl text-brand">◷</span><p class="mt-5 font-bold">Lacak progres</p><p class="mt-2 text-xs leading-5 text-zinc-500">Lihat tahapan pengerjaan motor.</p><p class="mt-5 text-xs text-brand">Lihat status →</p></button>
                    <button onclick="switchTab('parts')" class="rounded-2xl border border-white/10 bg-panel p-5 text-left transition hover:scale-[1.02] hover:border-brand/50"><span class="text-2xl text-brand">▦</span><p class="mt-5 font-bold">Cari sparepart</p><p class="mt-2 text-xs leading-5 text-zinc-500">Temukan kebutuhan motor kamu.</p><p class="mt-5 text-xs text-brand">Buka katalog →</p></button>
                    <button onclick="switchTab('invoice')" class="rounded-2xl border border-white/10 bg-panel p-5 text-left transition hover:scale-[1.02] hover:border-brand/50"><span class="text-2xl text-brand">▤</span><p class="mt-5 font-bold">Nota digital</p><p class="mt-2 text-xs leading-5 text-zinc-500">Tinjau rincian biaya transaksi.</p><p class="mt-5 text-xs text-brand">Lihat nota →</p></button>
                </div>
                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-panel p-5"><p class="text-xs text-zinc-500">Paket servis aktif</p><p class="mt-2 text-3xl font-black">{{ $services->count() }}</p></div>
                    <div class="rounded-2xl border border-white/10 bg-panel p-5"><p class="text-xs text-zinc-500">Produk sparepart</p><p class="mt-2 text-3xl font-black">{{ $spareparts->count() }}</p></div>
                    <div class="rounded-2xl border border-white/10 bg-panel p-5"><p class="text-xs text-zinc-500">Status bengkel</p><p class="mt-2 text-xl font-black text-emerald-300">Menerima booking</p></div>
                </div>
            </section>

            <section id="page-booking" class="page hidden">
                <div class="mb-7"><p class="text-xs font-bold tracking-[.2em] text-brand">JADWALKAN KUNJUNGAN</p><h1 class="mt-2 text-3xl font-black">Booking Servis</h1><p class="mt-2 text-sm text-zinc-500">Lengkapi detail kendaraan dan pilih paket servis yang dibutuhkan.</p></div>
                <div class="grid gap-6 xl:grid-cols-[1.1fr_.9fr]">
                    <form id="booking-form" action="{{ route('booking.store') }}" method="POST" class="rounded-2xl border border-white/10 bg-panel p-5 md:p-7">
                        @csrf
                        <h2 class="text-lg font-bold">Informasi kendaraan</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label class="text-xs text-zinc-400">Merek motor<input name="merek_motor" required maxlength="80" placeholder="Contoh: Honda" class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm text-white outline-none focus:border-brand"></label>
                            <label class="text-xs text-zinc-400">Tipe motor<input name="tipe_motor" required maxlength="100" placeholder="Contoh: Vario 160" class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm text-white outline-none focus:border-brand"></label>
                            <label class="text-xs text-zinc-400 sm:col-span-2">Nomor plat<input name="nomor_plat" required maxlength="20" placeholder="Contoh: BK 1234 XYZ" class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm uppercase text-white outline-none focus:border-brand"></label>
                            <label class="text-xs text-zinc-400 sm:col-span-2">Keluhan utama<textarea name="keluhan" required maxlength="1500" rows="3" placeholder="Ceritakan kondisi motor..." class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm text-white outline-none focus:border-brand"></textarea></label>
                            <label class="text-xs text-zinc-400">Tanggal servis<input type="date" name="tanggal" required min="{{ now()->toDateString() }}" class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm text-white outline-none focus:border-brand"></label>
                            <label class="text-xs text-zinc-400">Jam kedatangan<input type="time" name="jam" required min="08:00" max="20:00" class="mt-2 w-full rounded-xl border border-white/10 bg-[#101013] px-3 py-3 text-sm text-white outline-none focus:border-brand"></label>
                        </div>
                        <div class="mt-6"><h3 class="text-sm font-bold">Pilih mekanik</h3>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <label class="mechanic-option cursor-pointer rounded-xl border border-brand/60 bg-brand/5 p-3 transition hover:scale-[1.02]"><input type="radio" name="mekanik_name" value="Arya Nugraha" checked class="accent-orange-500"><span class="ml-2 text-sm font-semibold">AN · Arya Nugraha</span><span class="mt-2 block text-[10px] text-emerald-300">● Tersedia</span></label>
                                <label class="mechanic-option cursor-pointer rounded-xl border border-white/10 p-3 transition hover:scale-[1.02]"><input type="radio" name="mekanik_name" value="Bima Kurniawan" class="accent-orange-500"><span class="ml-2 text-sm font-semibold">BK · Bima Kurniawan</span><span class="mt-2 block text-[10px] text-amber-300">● Sedang servis</span></label>
                            </div>
                        </div>
                        <div class="mt-6 rounded-xl border border-white/10 bg-[#111114] p-4"><p class="text-xs text-zinc-500">Estimasi biaya awal</p><p id="booking-total" class="mt-1 text-2xl font-black text-brand">Rp 85.000</p><p class="mt-1 text-[10px] text-zinc-500">Belum termasuk biaya tambahan jika ditemukan komponen lain.</p></div>
                        <button type="submit" class="mt-5 w-full rounded-xl bg-brand px-5 py-3.5 text-sm font-black text-black transition hover:scale-[1.01] hover:bg-orange-400">Konfirmasi booking →</button>
                    </form>
                    <div>
                        <div class="rounded-2xl border border-white/10 bg-panel p-5 md:p-7">
                            <div class="flex items-center justify-between"><h2 class="text-lg font-bold">Service Package</h2><span class="text-[10px] text-zinc-500">PILIH SATU</span></div>
                            <div class="mt-4 space-y-3">
                                @foreach ($services as $service)
                                <label class="service-card block cursor-pointer rounded-xl border border-white/10 bg-[#111114] p-4 transition hover:scale-[1.01] hover:border-brand/60" data-price="{{ $service->harga }}">
                                    <div class="flex items-start gap-3"><input type="radio" name="service_choice" value="{{ $service->id }}" data-price="{{ $service->harga }}" class="service-radio mt-1 accent-orange-500" {{ $loop->first ? 'checked' : '' }} form="booking-form-helper">
                                        <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center justify-between gap-2"><p class="font-bold">{{ $service->nama_jasa }}</p><p class="font-black text-brand">Rp {{ number_format($service->harga, 0, ',', '.') }}</p></div><p class="mt-1 text-xs text-zinc-500">{{ $service->kategori }} · Estimasi {{ $service->estimasi_waktu }}</p></div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <p class="mt-4 text-xs leading-5 text-zinc-500">Harga paket menjadi estimasi awal. Teknisi akan mengonfirmasi apabila ada komponen tambahan.</p>
                        </div>
                        <div class="mt-5 rounded-2xl border border-white/10 bg-panel p-5"><h3 class="font-bold">Informasi booking</h3><p class="mt-2 text-xs leading-6 text-zinc-500">Jam operasional 08.00–20.00 WIB. Mohon datang 10 menit sebelum jadwal servis.</p><a href="{{ route('tracking.index') }}" class="mt-4 inline-flex rounded-lg border border-brand/30 px-3 py-2 text-xs font-bold text-brand">Lihat booking & tracking →</a><div class="mt-4 flex gap-3 rounded-xl bg-emerald-500/10 p-3 text-xs text-emerald-200"><span>✓</span><span>Data booking dikirim dengan validasi server dan perlindungan CSRF.</span></div></div>
                    </div>
                </div>
            </section>

            <section id="page-tracking" class="page hidden">
                <p class="text-xs font-bold tracking-[.2em] text-brand">PEMANTAUAN REAL-TIME DEMO</p><h1 class="mt-2 text-3xl font-black">Live Tracking</h1><p class="mt-2 text-sm text-zinc-500">Ikuti tahapan servis kendaraanmu.</p>
                <div class="mt-7 grid gap-5 xl:grid-cols-[.8fr_1.2fr]">
                    <div class="rounded-2xl border border-white/10 bg-panel p-6"><p class="text-xs text-zinc-500">KODE BOOKING</p><p class="mt-2 break-all text-2xl font-black tracking-wide text-brand">{{ $activeBooking?->kode_booking ?? 'BK-101026-AX7F' }}</p><div class="my-6 border-t border-white/10"></div>
                        <p class="text-xs text-zinc-500">KENDARAAN</p><p class="mt-1 text-lg font-bold">{{ $activeBooking ? $activeBooking->merek_motor.' '.$activeBooking->tipe_motor : 'Honda Vario 160' }}</p><p class="text-sm text-zinc-400">{{ $activeBooking?->nomor_plat ?? 'B 4821 KJD' }}</p>
                        <div class="mt-5 grid grid-cols-2 gap-4"><div><p class="text-xs text-zinc-500">Mekanik</p><p class="mt-1 text-sm font-semibold">{{ $activeBooking?->mekanik_name ?? 'Arya Nugraha' }}</p></div><div><p class="text-xs text-zinc-500">Estimasi selesai</p><p class="mt-1 text-sm font-semibold">Sekitar 12:00 WIB</p></div></div>
                        <div class="mt-6 rounded-xl border border-white/10 bg-[#111114] p-4"><p class="text-xs text-zinc-500">PAKET SERVIS</p><p class="mt-1 font-bold">{{ $activeBooking?->service?->nama_jasa ?? 'CVT Performance' }}</p></div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-panel p-6"><h2 class="font-bold">STATUS PENGERJAAN</h2><div class="relative mt-6 space-y-0">
                        @php $steps = [['Antrian','Selesai'],['Pengecekan','Selesai'],['Pengerjaan','Sedang Berjalan'],['Test Drive','Menunggu'],['Selesai','Menunggu']]; @endphp
                        @foreach ($steps as $i => $step)
                        <div class="relative flex gap-4 pb-7 last:pb-0">
                            @if (!$loop->last)<div class="absolute left-[11px] top-6 h-full w-px bg-white/10"></div>@endif
                            <div class="relative z-10 mt-1 h-6 w-6 shrink-0 rounded-full border {{ $i < 2 ? 'border-emerald-400 bg-emerald-400 text-black' : ($i === 2 ? 'pulse-dot border-brand bg-brand text-black' : 'border-zinc-700 bg-[#111114]') }} grid place-items-center text-[10px]">{{ $i < 2 ? '✓' : ($i === 2 ? '•' : '') }}</div>
                            <div class="flex-1"><div class="flex flex-wrap items-center justify-between gap-2"><p class="text-sm font-bold {{ $i===2 ? 'text-brand' : '' }}">{{ $step[0] }}</p><span class="text-[10px] {{ $i < 2 ? 'text-emerald-300' : ($i===2 ? 'text-brand' : 'text-zinc-500') }}">{{ $step[1] }}</span></div>
                                @if ($i === 2)<div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/10"><div class="h-full w-[68%] rounded-full bg-brand shadow-[0_0_12px_#ff6b00]"></div></div><p class="mt-2 text-[10px] text-zinc-500">Progres pengerjaan 68%</p>@endif
                            </div>
                        </div>
                        @endforeach
                    </div><div class="mt-7 rounded-xl border border-brand/20 bg-brand/5 p-4"><p class="text-xs font-bold text-brand">MECHANIC NOTE</p><p class="mt-2 text-sm leading-6 text-zinc-300">Pemeriksaan awal selesai. Mekanik sedang melakukan perawatan CVT dan pengecekan respons akselerasi.</p></div></div>
                </div>
            </section>

            <section id="page-parts" class="page hidden">
                <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-bold tracking-[.2em] text-brand">KOMPONEN PILIHAN</p><h1 class="mt-2 text-3xl font-black">KATALOG SPAREPART</h1><p class="mt-2 text-sm text-zinc-500">Cari komponen berkualitas untuk performa optimal.</p></div>
                    <label class="w-full sm:w-72"><span class="sr-only">Cari sparepart</span><input id="part-search" type="search" placeholder="⌕  Cari sparepart..." class="w-full rounded-xl border border-white/10 bg-panel px-4 py-3 text-sm outline-none focus:border-brand"></label>
                </div>
                <div id="category-filters" class="mt-6 flex gap-2 overflow-x-auto pb-2">
                    @foreach (['Semua','Oli & Pelumas','Kampas Rem','Belt & Chain','Kelistrikan','Ban & Velg'] as $category)
                    <button data-category="{{ $category }}" class="filter-pill shrink-0 rounded-full border {{ $loop->first ? 'border-brand bg-brand text-black' : 'border-white/10 bg-panel text-zinc-400' }} px-4 py-2 text-xs font-semibold transition hover:scale-[1.02]">{{ $category }}</button>
                    @endforeach
                </div>
                <div id="parts-grid" class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($spareparts as $part)
                    <article class="part-card group rounded-2xl border border-white/10 bg-panel p-5 transition duration-200 hover:scale-[1.02] hover:border-brand/50" data-name="{{ strtolower($part->nama_sparepart) }}" data-category="{{ $part->kategori }}">
                        <div class="grid h-36 place-items-center rounded-xl border border-white/5 bg-gradient-to-br from-[#242428] to-[#111114]"><span class="text-5xl text-brand">⚙</span></div>
                        <div class="mt-4 flex items-center justify-between gap-2"><span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] text-emerald-300">{{ $part->stok }} in stock</span><span class="text-[10px] text-zinc-500">{{ $part->kategori }}</span></div>
                        <h2 class="mt-3 font-bold">{{ $part->nama_sparepart }}</h2><p class="mt-2 text-xl font-black text-brand">Rp {{ number_format($part->harga, 0, ',', '.') }}</p>
                        <button type="button" class="cart-btn mt-4 w-full rounded-xl border border-white/10 px-4 py-3 text-xs font-bold transition hover:border-brand hover:bg-brand hover:text-black" data-product="{{ $part->nama_sparepart }}">＋ Tambah ke keranjang</button>
                    </article>
                    @endforeach
                </div>
                <p id="no-parts" class="hidden py-12 text-center text-sm text-zinc-500">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</p>
            </section>

            <section id="page-invoice" class="page hidden">
                <p class="no-print text-xs font-bold tracking-[.2em] text-brand">RINGKASAN TRANSAKSI</p><h1 class="no-print mt-2 text-3xl font-black">Nota Digital</h1><p class="no-print mt-2 text-sm text-zinc-500">Tinjau rincian transaksi dan cetak sebagai PDF.</p>
                <div class="mt-7 grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_260px]">
                    <div class="receipt rounded-2xl bg-[#f6f1e8] p-6 text-[#202020] shadow-2xl sm:p-9">
                        <div class="flex items-start justify-between gap-4 border-b border-dashed border-zinc-300 pb-5"><div><p class="text-xs font-bold tracking-[.2em] text-orange-700">BENGKEL ORANGE</p><h2 class="mt-2 text-2xl font-black">MOTOFIX</h2><p class="mt-1 text-xs text-zinc-500">Digital Service Receipt</p></div><div class="text-right"><p class="text-xs text-zinc-500">NO. NOTA</p><p class="mt-1 text-sm font-bold">INV-101026-001</p><p class="mt-1 text-xs text-zinc-500">{{ now()->format('d M Y') }}</p></div></div>
                        <div class="grid gap-4 border-b border-dashed border-zinc-300 py-5 sm:grid-cols-2"><div><p class="text-[10px] uppercase tracking-widest text-zinc-500">Pelanggan</p><p class="mt-1 font-bold">Dimas Saputra</p><p class="text-xs text-zinc-600">Honda Vario 160 · B 4821 KJD</p></div><div class="sm:text-right"><p class="text-[10px] uppercase tracking-widest text-zinc-500">Kode booking</p><p class="mt-1 font-bold">{{ $activeBooking?->kode_booking ?? 'BK-101026-AX7F' }}</p><p class="text-xs text-zinc-600">Mekanik: Arya Nugraha</p></div></div>
                        <div class="py-5"><div class="mb-4 flex justify-between text-[10px] font-bold uppercase tracking-widest text-zinc-500"><span>Deskripsi</span><span>Jumlah</span></div><div class="space-y-4 text-sm"><div class="flex justify-between gap-4"><span>CVT Performance</span><span class="font-semibold">Rp 185.000</span></div><div class="flex justify-between gap-4"><span>Inspection</span><span class="font-semibold">Rp 35.000</span></div><div class="flex justify-between gap-4 text-emerald-700"><span>Diskon member</span><span class="font-semibold">− Rp 20.000</span></div></div></div>
                        <div class="border-t border-dashed border-zinc-300 pt-5"><div class="flex items-center justify-between gap-4"><span class="text-sm font-bold">TOTAL TAGIHAN</span><span class="text-2xl font-black">Rp 200.000</span></div><p class="mt-3 text-xs font-bold text-red-600">● Belum dibayar</p></div>
                        <p class="mt-7 text-center text-[10px] leading-5 text-zinc-500">Terima kasih telah mempercayakan perawatan motor Anda kepada Bengkel Orange.<br>Simpan nota ini sebagai bukti transaksi.</p>
                    </div>
                    <div class="no-print space-y-4">
                        <div class="rounded-2xl border border-white/10 bg-panel p-5"><h3 class="font-bold">Aksi nota</h3><p class="mt-2 text-xs leading-5 text-zinc-500">Cetak nota atau pilih opsi “Save as PDF” pada dialog printer.</p><button onclick="window.print()" class="mt-4 w-full rounded-xl bg-brand px-4 py-3 text-sm font-black text-black transition hover:scale-[1.02]">↧ Cetak / simpan PDF</button></div>
                        <div class="rounded-2xl border border-white/10 bg-panel p-5"><h3 class="font-bold">Payment Methods</h3><p class="mt-1 text-xs text-zinc-500">Metode pembayaran yang didukung</p><div class="mt-4 grid grid-cols-2 gap-2"><div class="rounded-lg border border-white/10 bg-[#111114] p-3 text-center text-xs font-bold">QRIS</div><div class="rounded-lg border border-white/10 bg-[#111114] p-3 text-center text-xs font-bold">BANK</div><div class="rounded-lg border border-white/10 bg-[#111114] p-3 text-center text-xs font-bold">DEBIT</div><div class="rounded-lg border border-white/10 bg-[#111114] p-3 text-center text-xs font-bold">CASH</div></div><p class="mt-4 text-[10px] leading-5 text-zinc-500">Panel metode pembayaran bersifat informasi demo; gateway pembayaran belum diintegrasikan.</p></div>
                    </div>
                </div>
            </section>
            <footer class="no-print mt-10 border-t border-white/10 py-5 text-xs text-zinc-600">© {{ date('Y') }} ProjectMotofixx · Bengkel Orange / MOTOFIX</footer>
        </div>
    </main>
</div>

<script>
    // Navigasi tab SPA: menukar panel tanpa memuat ulang halaman.
    const tabNames = { home: 'Beranda', booking: 'Booking Servis', tracking: 'Live Tracking', parts: 'Sparepart', invoice: 'Nota Digital' };
    function switchTab(tab) {
        document.querySelectorAll('.page').forEach(page => page.classList.add('hidden'));
        const target = document.getElementById(`page-${tab}`);
        if (target) target.classList.remove('hidden');
        document.querySelectorAll('.nav-btn').forEach(btn => {
            const active = btn.dataset.tab === tab;
            btn.classList.toggle('active', active);
            btn.classList.toggle('text-zinc-400', !active);
        });
        document.getElementById('crumb').textContent = tabNames[tab] || 'Dashboard';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    document.querySelectorAll('.nav-btn').forEach(btn => btn.addEventListener('click', () => switchTab(btn.dataset.tab)));

    // Toast kecil dengan animasi masuk dan keluar.
    let toastTimer;
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden', 'hide');
        toast.classList.add('toast');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.classList.add('hidden'), 260);
        }, 3000);
    }

    // Pemilihan paket: harga dihitung ulang secara real-time.
    const serviceCards = [...document.querySelectorAll('.service-card')];
    const bookingTotal = document.getElementById('booking-total');
    const bookingForm = document.querySelector('form[action="{{ route('booking.store') }}"]');
    serviceCards.forEach((card, index) => {
        const radio = card.querySelector('.service-radio');
        radio.setAttribute('form', 'booking-form');
        radio.name = 'service_id';
        radio.addEventListener('change', () => updateServiceSelection());
        if (index === 0) radio.checked = true;
    });
    function updateServiceSelection() {
        let price = 0;
        serviceCards.forEach(card => {
            const radio = card.querySelector('.service-radio');
            const selected = radio.checked;
            card.classList.toggle('border-brand', selected);
            card.classList.toggle('bg-brand/5', selected);
            card.classList.toggle('border-white/10', !selected);
            if (selected) price = Number(radio.dataset.price || 0);
        });
        bookingTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
    }
    updateServiceSelection();

    // Sorot pilihan mekanik agar pengguna melihat pilihan yang aktif.
    document.querySelectorAll('.mechanic-option input').forEach(input => input.addEventListener('change', () => {
        document.querySelectorAll('.mechanic-option').forEach(label => {
            const checked = label.querySelector('input').checked;
            label.classList.toggle('border-brand/60', checked);
            label.classList.toggle('bg-brand/5', checked);
            label.classList.toggle('border-white/10', !checked);
        });
    }));

    // Pencarian dan filter kategori katalog berjalan langsung di browser.
    let activeCategory = 'Semua';
    const searchInput = document.getElementById('part-search');
    const partCards = [...document.querySelectorAll('.part-card')];
    function filterParts() {
        const query = (searchInput.value || '').trim().toLocaleLowerCase('id-ID');
        let visible = 0;
        partCards.forEach(card => {
            const matchesName = card.dataset.name.includes(query);
            const matchesCategory = activeCategory === 'Semua' || card.dataset.category === activeCategory;
            const show = matchesName && matchesCategory;
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        document.getElementById('no-parts').classList.toggle('hidden', visible > 0);
    }
    searchInput.addEventListener('input', filterParts);
    document.querySelectorAll('.filter-pill').forEach(pill => pill.addEventListener('click', () => {
        activeCategory = pill.dataset.category;
        document.querySelectorAll('.filter-pill').forEach(item => {
            const active = item === pill;
            item.classList.toggle('border-brand', active);
            item.classList.toggle('bg-brand', active);
            item.classList.toggle('text-black', active);
            item.classList.toggle('border-white/10', !active);
            item.classList.toggle('bg-panel', !active);
            item.classList.toggle('text-zinc-400', !active);
        });
        filterParts();
    }));

    // Tombol keranjang demo: tampilkan notifikasi, belum melakukan checkout.
    document.querySelectorAll('.cart-btn').forEach(button => button.addEventListener('click', () => {
        showToast(`${button.dataset.product} ditambahkan ke keranjang demo.`);
    }));

    // Menampilkan notifikasi flash dari server setelah booking berhasil.
    @if (session('success'))
        showToast(@json(session('success')));
    @endif
</script>
</body>
</html>
