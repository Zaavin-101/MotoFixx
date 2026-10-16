<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Service;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BengkelController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'services' => Service::where('status', true)->orderBy('harga')->get(),
            'featuredParts' => Sparepart::where('stok', '>', 0)->orderBy('nama_sparepart')->take(6)->get(),
        ]);
    }

    public function index(): View|RedirectResponse
    {
        if (auth()->user()->isAdmin()) return redirect()->route('admin.dashboard');
        $bookings = auth()->user()->bookings()->with('service')->latest()->take(6)->get();
        return view('customer.dashboard', [
            'recentBookings' => $bookings,
            'activeBooking' => $bookings->first(fn ($b) => $b->status_pengerjaan !== 'selesai'),
            'totalBookings' => auth()->user()->bookings()->count(),
            'services' => Service::where('status', true)->orderBy('harga')->get(),
            'sparepartsCount' => Sparepart::where('stok', '>', 0)->count(),
            'unreadUpdates' => BookingStatusHistory::whereIn('booking_id', auth()->user()->bookings()->select('id'))->where('created_at', '>=', now()->subDays(14))->count(),
        ]);
    }

    public function createBooking(): View|RedirectResponse
    {
        abort_if(auth()->user()->isAdmin(), 403, 'Admin tidak membuat booking pelanggan.');
        return view('booking.create', ['services' => Service::where('status', true)->orderBy('harga')->get()]);
    }

    public function invoice(Request $request, Booking $booking): View
    {
        abort_unless($request->user()->isAdmin() || (int) $booking->user_id === (int) $request->user()->id, 403);
        $booking->load(['service', 'user']);
        return view('booking.invoice', compact('booking'));
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        abort_if($request->user()->isAdmin(), 403, 'Akun admin tidak dapat membuat booking pelanggan.');
        $validated = $request->validate([
            'merek_motor' => ['required', 'string', 'max:80'],
            'tipe_motor' => ['required', 'string', 'max:100'],
            'nomor_plat' => ['required', 'string', 'max:20'],
            'keluhan' => ['required', 'string', 'max:1500'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam' => ['required', 'date_format:H:i', 'after_or_equal:08:00', 'before_or_equal:20:00'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'mekanik_name' => ['required', 'in:Arya Nugraha,Bima Kurniawan'],
            'payment_method' => ['required', 'in:qris,transfer_bank,debit,tunai'],
        ]);
        $service = Service::where('status', true)->findOrFail($validated['service_id']);
        do { $code = 'BK-' . random_int(100000, 999999) . '-' . strtoupper(Str::random(4)); }
        while (Booking::where('kode_booking', $code)->exists());
        $booking = Booking::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'nama_pelanggan' => $request->user()->name,
            'kode_booking' => $code,
            'status_pengerjaan' => 'antrian',
            'status_pembayaran' => 'belum_dibayar',
            'total_harga' => $service->harga,
        ]);
        BookingStatusHistory::create([
            'booking_id' => $booking->id, 'status' => 'antrian',
            'keterangan' => 'Booking dibuat dan masuk antrean.', 'changed_by' => $request->user()->id,
        ]);
        return redirect()->route('booking.invoice', $booking)->with('success', "Booking berhasil dibuat. Kode booking: {$code}");
    }
}
