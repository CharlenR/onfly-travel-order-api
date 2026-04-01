<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('travel-orders', TravelOrderController::class);
    
    Route::patch('travel-orders/{travelOrder}/approve', [TravelOrderController::class, 'approve']);
    Route::patch('travel-orders/{travelOrder}/cancel', [TravelOrderController::class, 'cancel']);    
});