<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Sparepart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403, 'Halaman ini khusus admin bengkel.');
    }

    public function index(): View
    {
        $this->ensureAdmin();

        return view('admin.dashboard', [
            'totalBooking' => Booking::count(),
            'bookingAktif' => Booking::where('status_pengerjaan', '!=', 'selesai')->count(),
            'bookingSelesai' => Booking::where('status_pengerjaan', 'selesai')->count(),
            'totalPendapatan' => Booking::where('status_pembayaran', 'lunas')->sum('total_harga'),
            'totalLayanan' => Service::count(),
            'totalSparepart' => Sparepart::count(),
            'bookings' => Booking::with(['service', 'user'])->latest()->take(12)->get(),
            'services' => Service::orderBy('nama_jasa')->get(),
            'spareparts' => Sparepart::orderBy('nama_sparepart')->get(),
        ]);
    }

    public function storeService(Request $request): RedirectResponse
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nama_jasa' => ['required','string','max:120'],
            'kategori' => ['required','string','max:80'],
            'harga' => ['required','integer','min:0'],
            'estimasi_waktu' => ['required','string','max:60'],
            'status' => ['nullable','boolean'],
        ]);
        $data['status'] = $request->boolean('status');
        Service::create($data);
        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function updateService(Request $request, Service $service): RedirectResponse
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nama_jasa' => ['required','string','max:120'],
            'kategori' => ['required','string','max:80'],
            'harga' => ['required','integer','min:0'],
            'estimasi_waktu' => ['required','string','max:60'],
            'status' => ['nullable','boolean'],
        ]);
        $data['status'] = $request->boolean('status');
        $service->update($data);
        return back()->with('success', 'Layanan berhasil diperbarui.');
    }

    public function deleteService(Service $service): RedirectResponse
    {
        $this->ensureAdmin();
        if ($service->bookings()->exists()) {
            return back()->withErrors(['service' => 'Layanan ini sudah digunakan pada booking dan tidak dapat dihapus. Nonaktifkan layanan jika tidak dipakai lagi.']);
        }
        $service->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function storeSparepart(Request $request): RedirectResponse
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nama_sparepart' => ['required','string','max:150'],
            'kategori' => ['required','string','max:80'],
            'harga' => ['required','integer','min:0'],
            'stok' => ['required','integer','min:0'],
            'gambar' => ['nullable','url','max:2048'],
        ]);
        Sparepart::create($data);
        return back()->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function updateSparepart(Request $request, Sparepart $sparepart): RedirectResponse
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nama_sparepart' => ['required','string','max:150'],
            'kategori' => ['required','string','max:80'],
            'harga' => ['required','integer','min:0'],
            'stok' => ['required','integer','min:0'],
            'gambar' => ['nullable','url','max:2048'],
        ]);
        $sparepart->update($data);
        return back()->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function deleteSparepart(Sparepart $sparepart): RedirectResponse
    {
        $this->ensureAdmin();
        $sparepart->delete();
        return back()->with('success', 'Sparepart berhasil dihapus dari katalog.');
    }

    public function updatePayment(Request $request, Booking $booking): RedirectResponse
    {
        $this->ensureAdmin();
        $data = $request->validate(['status_pembayaran' => ['required', Rule::in(['belum_dibayar','lunas'])]]);
        $booking->update($data);
        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
