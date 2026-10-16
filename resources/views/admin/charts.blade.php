<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Analytics — Motofix</title><script src="https://cdn.tailwindcss.com"></script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script>tailwind.config={theme:{extend:{colors:{brand:'#FF6B00',ink:'#0B0B0C',panel:'#1A1A1E'}}}}</script></head>
<body class="min-h-screen bg-ink p-5 text-white md:p-10">
<main class="mx-auto max-w-7xl">
<div class="flex flex-wrap items-center justify-between gap-4"><div><p class="text-xs font-bold tracking-[.2em] text-brand">MOTOFIX ANALYTICS</p><h1 class="mt-2 text-3xl font-black">Dashboard Statistik</h1><p class="mt-2 text-sm text-zinc-400">Data dihitung dari database booking dan pembayaran.</p></div><a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 px-4 py-2 text-sm">← Dashboard</a></div>
<div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
@foreach([['Total booking',$totalBookings],['Servis aktif',$activeBookings],['Servis selesai',$completedBookings],['Pendapatan lunas','Rp '.number_format($paidRevenue,0,',','.')]] as $metric)
<div class="rounded-2xl border border-white/10 bg-panel p-5"><p class="text-sm text-zinc-400">{{ $metric[0] }}</p><p class="mt-3 text-2xl font-black text-brand">{{ $metric[1] }}</p></div>
@endforeach
</div>
<div class="mt-5 grid gap-5 xl:grid-cols-2">
<section class="rounded-2xl border border-white/10 bg-panel p-5"><h2 class="font-bold">Booking per hari · 7 hari terakhir</h2><div class="mt-4"><canvas id="daily-chart" height="130"></canvas></div></section>
<section class="rounded-2xl border border-white/10 bg-panel p-5"><h2 class="font-bold">Status pengerjaan</h2><div class="mt-4"><canvas id="status-chart" height="160"></canvas></div></section>
<section class="rounded-2xl border border-white/10 bg-panel p-5 xl:col-span-2"><h2 class="font-bold">Pendapatan lunas · 7 hari terakhir</h2><div class="mt-4"><canvas id="revenue-chart" height="100"></canvas></div><p class="mt-3 text-xs text-zinc-500">Pendapatan hanya menghitung booking dengan status pembayaran “Lunas”.</p></section>
</div>
</main>
<script>
const labels = @json($dailyLabels), bookingData = @json($dailyValues);
const statusLabels = @json($statusLabels), statusData = @json($statusValues);
const revenueLabels = @json($revenueLabels), revenueData = @json($revenueValues);
Chart.defaults.color = '#a1a1aa'; Chart.defaults.borderColor = 'rgba(255,255,255,.08)';
new Chart(document.getElementById('daily-chart'), {type:'bar',data:{labels,datasets:[{label:'Booking',data:bookingData,backgroundColor:'#FF6B00',borderRadius:6}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}});
new Chart(document.getElementById('status-chart'), {type:'doughnut',data:{labels:statusLabels,datasets:[{data:statusData,backgroundColor:['#FF6B00','#F59E0B','#FB923C','#A3A3A3','#22C55E'],borderColor:'#1A1A1E'}]},options:{responsive:true,plugins:{legend:{position:'bottom'}}}});
new Chart(document.getElementById('revenue-chart'), {type:'line',data:{labels:revenueLabels,datasets:[{label:'Pendapatan (Rp)',data:revenueData,borderColor:'#FF6B00',backgroundColor:'rgba(255,107,0,.12)',fill:true,tension:.3}]},options:{responsive:true,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>'Rp '+new Intl.NumberFormat('id-ID').format(c.raw)}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rp '+new Intl.NumberFormat('id-ID',{notation:'compact'}).format(v)}}}}});
</script>
</body></html>
