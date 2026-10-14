<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin' ? view('admin.dashboard') : view('customer.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/bookings', 'admin.dashboard')->name('bookings');
    Route::view('/spareparts', 'admin.dashboard')->name('spareparts');
    Route::view('/services', 'admin.dashboard')->name('services');
    Route::view('/mechanics', 'admin.dashboard')->name('mechanics');
});

require __DIR__.'/auth.php';
