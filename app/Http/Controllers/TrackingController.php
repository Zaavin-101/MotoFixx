<?php

namespace App\Http\Controllers;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()->isAdmin()
            ? Booking::with('service', 'user')->latest()->get()
            : $request->user()->bookings()->with('service', 'statusHistories')->latest()->get();

        return view('tracking.index', compact('bookings'));
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($request->user()->isAdmin() || $booking->user_id === $request->user()->id, 403);
        $booking->load('service', 'user', 'statusHistories');

        return view('tracking.show', compact('booking'));
    }

    public function status(Request $request, Booking $booking): JsonResponse
    {
        abort_unless($request->user()->isAdmin() || $booking->user_id === $request->user()->id, 403);

        return response()->json([
            'id' => $booking->id,
            'kode_booking' => $booking->kode_booking,
            'status' => $booking->status_pengerjaan,
            'status_label' => $this->label($booking->status_pengerjaan),
            'updated_at' => $booking->updated_at?->toIso8601String(),
            'histories' => $booking->statusHistories()->latest()->get()->map(fn ($history) => [
                'status' => $history->status,
                'label' => $this->label($history->status),
                'keterangan' => $history->keterangan,
                'created_at' => $history->created_at?->format('d M Y H:i'),
            ]),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'status_pengerjaan' => ['required', 'in:antrian,pengecekan,pengerjaan,test_drive,selesai'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'status_pembayaran' => ['required', 'in:belum_dibayar,lunas'],
        ]);

        $booking->update([
            'status_pengerjaan' => $data['status_pengerjaan'],
            'status_pembayaran' => $data['status_pembayaran'],
        ]);

        $history = BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => $data['status_pengerjaan'],
            'keterangan' => $data['keterangan'] ?: 'Status diperbarui oleh tim bengkel.',
            'changed_by' => $request->user()->id,
        ]);

        $booking->load('user');
        if ($booking->user) {
            broadcast(new BookingStatusUpdated($booking, $history))->toOthers();
        }

        return back()->with('success', 'Status booking berhasil diperbarui dan notifikasi realtime dikirim.');
    }

    private function label(string $status): string
    {
        return [
            'antrian' => 'Antrian',
            'pengecekan' => 'Pengecekan',
            'pengerjaan' => 'Pengerjaan',
            'test_drive' => 'Test Drive',
            'selesai' => 'Selesai',
        ][$status] ?? ucfirst($status);
    }
}
