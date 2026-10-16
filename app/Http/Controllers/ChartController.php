<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChartController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $statusOrder = ['antrian', 'pengecekan', 'pengerjaan', 'test_drive', 'selesai'];
        $statusCounts = Booking::query()
            ->select('status_pengerjaan', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pengerjaan')
            ->pluck('total', 'status_pengerjaan');

        $dailyBookings = Booking::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')->orderBy('day')->get();

        $revenue = Booking::query()
            ->selectRaw('DATE(updated_at) as day, SUM(total_harga) as total')
            ->where('status_pembayaran', 'lunas')
            ->where('updated_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')->orderBy('day')->get();

        return view('admin.charts', [
            'totalBookings' => Booking::count(),
            'activeBookings' => Booking::where('status_pengerjaan', '!=', 'selesai')->count(),
            'completedBookings' => Booking::where('status_pengerjaan', 'selesai')->count(),
            'paidRevenue' => Booking::where('status_pembayaran', 'lunas')->sum('total_harga'),
            'statusLabels' => array_map(fn ($s) => ucfirst(str_replace('_', ' ', $s)), $statusOrder),
            'statusValues' => array_map(fn ($s) => (int) ($statusCounts[$s] ?? 0), $statusOrder),
            'dailyLabels' => $dailyBookings->map(fn ($r) => \Carbon\Carbon::parse($r->day)->format('d M'))->values(),
            'dailyValues' => $dailyBookings->pluck('total')->map(fn ($v) => (int) $v)->values(),
            'revenueLabels' => $revenue->map(fn ($r) => \Carbon\Carbon::parse($r->day)->format('d M'))->values(),
            'revenueValues' => $revenue->pluck('total')->map(fn ($v) => (int) $v)->values(),
        ]);
    }
}
