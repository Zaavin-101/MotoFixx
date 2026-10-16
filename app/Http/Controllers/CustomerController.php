<?php

namespace App\Http\Controllers;

use App\Models\BookingStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private function ensureCustomer(): void { abort_if(auth()->user()->isAdmin(), 403, 'Fitur ini khusus pelanggan.'); }

    public function profile(): View
    {
        $this->ensureCustomer();
        return view('customer.profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $this->ensureCustomer();
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:255',Rule::unique('users','email')->ignore($request->user()->id)],
            'current_password' => ['nullable','current_password'],
            'password' => ['nullable','confirmed',Password::min(8)],
        ]);
        $user = $request->user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            if (empty($data['current_password'])) return back()->withErrors(['current_password' => 'Isi password saat ini untuk mengganti password.'])->withInput();
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function notifications(Request $request): View
    {
        $this->ensureCustomer();
        $bookings = $request->user()->bookings()->with(['statusHistories' => fn ($q) => $q->latest()])->latest()->get();
        $updates = $bookings->flatMap(fn ($booking) => $booking->statusHistories->map(fn ($history) => [
            'booking' => $booking, 'history' => $history,
        ]))->sortByDesc(fn ($item) => $item['history']->created_at)->values();
        return view('customer.notifications', compact('updates'));
    }
}
