<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

Route::post('register', [DeviceController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('purchase', [DeviceController::class, 'purchase']);
    Route::get('check-subscription', [DeviceController::class, 'checkSubscription']);
});
