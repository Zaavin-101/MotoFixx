<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\MechanicTrackingController;
use App\Http\Controllers\MechanicDashboardController;
use App\Http\Controllers\VehicleController;
Route::get('/',[ServiceController::class,'home'])->name('home');
Route::get('/paket-servis',[ServiceController::class,'index'])->name('services.index');
Route::get('/sparepart',[SparepartController::class,'index'])->name('spareparts.index');
Route::get('/keranjang',[CartController::class,'index'])->name('cart.index');
Route::post('/keranjang/tambah',[CartController::class,'add'])->name('cart.add');
Route::patch('/keranjang',[CartController::class,'update'])->name('cart.update');
Route::delete('/keranjang',[CartController::class,'remove'])->name('cart.remove');
Route::middleware('guest')->group(function () {
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->middleware('throttle:5,1')->name('register.store');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::post('/payments/midtrans/notification',MidtransWebhookController::class)->name('payments.midtrans.notification');
Route::middleware('auth')->group(function () {
    Route::get('/checkout',[CheckoutController::class,'index'])->name('checkout');
    Route::post('/checkout',[CheckoutController::class,'store'])->middleware('throttle:10,1')->name('checkout.store');
    Route::get('/pesanan-saya',[CheckoutController::class,'orders'])->name('orders.index');
    Route::get('/pesanan/{order}',[CheckoutController::class,'show'])->name('orders.show');
    Route::post('/pesanan/{order}/payment/retry',[CheckoutController::class,'retryPayment'])->name('orders.payment.retry');
    Route::get('/pesanan/{order}/tracking',[MechanicTrackingController::class,'latest'])->name('orders.tracking');
    Route::get('/kendaraan',[VehicleController::class,'index'])->name('vehicles.index');
    Route::post('/kendaraan',[VehicleController::class,'store'])->name('vehicles.store');
});
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',[AdminDashboardController::class,'index'])->name('dashboard');
    Route::patch('/orders/{order}/status',[AdminDashboardController::class,'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/mark-cash-paid',[AdminDashboardController::class,'markCashPaid'])->name('orders.mark-cash-paid');
    Route::get('/inventory',[InventoryController::class,'index'])->name('inventory.index');
    Route::post('/inventory',[InventoryController::class,'store'])->name('inventory.store');
    Route::patch('/inventory/{sparepart}',[InventoryController::class,'update'])->name('inventory.update');
});
Route::middleware(['auth','role:mechanic'])->prefix('mekanik')->name('mechanic.')->group(function () {
    Route::get('/',[MechanicDashboardController::class,'index'])->name('dashboard');
    Route::patch('/pesanan/{order}/status',[MechanicDashboardController::class,'updateStatus'])->name('orders.status');
    Route::post('/pesanan/{order}/lokasi',[MechanicTrackingController::class,'update'])->name('orders.location');
});
