<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SparepartShopController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BengkelController::class, 'home'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BengkelController::class, 'index'])->name('dashboard');
    Route::get('/booking/create', [BengkelController::class, 'createBooking'])->name('booking.create');
    Route::post('/booking', [BengkelController::class, 'storeBooking'])->name('booking.store');
    Route::get('/booking/{booking}/invoice', [BengkelController::class, 'invoice'])->name('booking.invoice');
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/tracking/{booking}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/tracking/{booking}/status', [TrackingController::class, 'status'])->name('tracking.status');

    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications.index');

    Route::get('/spareparts', [SparepartShopController::class, 'index'])->name('spareparts.shop');
    Route::post('/spareparts/checkout', [SparepartShopController::class, 'checkout'])->name('spareparts.checkout');
    Route::get('/my-orders', [SparepartShopController::class, 'orders'])->name('spareparts.orders');
    Route::get('/my-orders/{order}', [SparepartShopController::class, 'showOrder'])->name('spareparts.orders.show');
});

// Admin routes are authenticated; each controller action also checks the admin/mechanic role.
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/charts', [ChartController::class, 'index'])->name('charts');
    Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
    Route::put('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [AdminController::class, 'deleteService'])->name('services.delete');
    Route::post('/spareparts', [AdminController::class, 'storeSparepart'])->name('spareparts.store');
    Route::put('/spareparts/{sparepart}', [AdminController::class, 'updateSparepart'])->name('spareparts.update');
    Route::delete('/spareparts/{sparepart}', [AdminController::class, 'deleteSparepart'])->name('spareparts.delete');
    Route::patch('/bookings/{booking}/status', [TrackingController::class, 'updateStatus'])->name('bookings.status');
    Route::patch('/bookings/{booking}/payment', [AdminController::class, 'updatePayment'])->name('bookings.payment');
});
