<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Motofix</title><script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:'#FF6B00',ink:'#0B0B0C',panel:'#1A1A1E'}}}}</script>
</head>
<body class="min-h-screen bg-ink text-white grid place-items-center p-5">
<main class="w-full max-w-md rounded-3xl border border-white/10 bg-panel p-7 shadow-2xl">
    <a href="{{ route('login') }}" class="text-xs font-bold tracking-[.2em] text-brand">BENGKEL ORANGE</a>
    <h1 class="mt-3 text-3xl font-black">Selamat datang.</h1><p class="mt-2 text-sm text-zinc-400">Login ke akun Motofix kamu.</p>
    @if(session('success'))<p class="mt-4 rounded-xl bg-emerald-500/10 p-3 text-sm text-emerald-300">{{ session('success') }}</p>@endif
    @if($errors->any())<div class="mt-4 rounded-xl bg-red-500/10 p-3 text-sm text-red-300">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
    <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">@csrf
        <label class="block text-sm">Email<input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-2 w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none focus:border-brand"></label>
        <label class="block text-sm">Password<input name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none focus:border-brand"></label>
        <label class="flex items-center gap-2 text-sm text-zinc-400"><input type="checkbox" name="remember" value="1" class="accent-orange-500"> Ingat saya</label>
        <button class="w-full rounded-xl bg-brand px-4 py-3 font-black text-black hover:bg-orange-400">Login →</button>
    </form>
    <p class="mt-5 text-center text-sm text-zinc-400">Belum punya akun? <a class="font-bold text-brand" href="{{ route('register') }}">Daftar</a></p>
</main>
</body></html>
