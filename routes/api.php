<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\OrderApiController;
Route::post('/login',[AuthApiController::class,'login'])->middleware('throttle:10,1');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthApiController::class,'logout']);
    Route::get('/orders',[OrderApiController::class,'index']);
    Route::get('/orders/{order}',[OrderApiController::class,'show']);
});
