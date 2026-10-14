<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,HomeController,BookingController,SparepartController,OrderController,InvoiceController,AdminController,MotorController};
Route::get('/home',[HomeController::class,'index']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::get('/spareparts',[SparepartController::class,'index']);
Route::get('/spareparts/{sparepart}',[SparepartController::class,'show']);
Route::middleware('auth:sanctum')->group(function(){
 Route::get('/user',[AuthController::class,'user']);Route::post('/logout',[AuthController::class,'logout']);
 Route::get('/motors',[MotorController::class,'index']);Route::post('/motors',[MotorController::class,'store']);Route::get('/booking-data',[BookingController::class,'data']);Route::get('/bookings',[BookingController::class,'index']);Route::post('/bookings',[BookingController::class,'store']);Route::get('/bookings/{booking}',[BookingController::class,'show']);Route::get('/bookings/{booking}/tracking',[BookingController::class,'tracking']);
 Route::get('/orders',[OrderController::class,'index']);Route::post('/checkout',[OrderController::class,'checkout']);Route::get('/invoices/{invoice}',[InvoiceController::class,'show']);
 Route::middleware('admin')->prefix('admin')->group(function(){Route::get('/dashboard',[AdminController::class,'dashboard']);Route::get('/bookings',[AdminController::class,'bookings']);Route::put('/bookings/{booking}',[AdminController::class,'updateBooking']);Route::get('/spareparts',[AdminController::class,'spareparts']);Route::post('/spareparts',[AdminController::class,'storeSparepart']);Route::put('/spareparts/{sparepart}',[AdminController::class,'updateSparepart']);Route::delete('/spareparts/{sparepart}',[AdminController::class,'deleteSparepart']);Route::get('/services',[AdminController::class,'services']);Route::get('/mechanics',[AdminController::class,'mechanics']);});
});
