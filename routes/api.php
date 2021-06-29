<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::post('register', [DeviceController::class, 'register']);
Route::post('purchase', [DeviceController::class, 'purchase']);
Route::get('check-subscription', [DeviceController::class, 'checkSubscription']);
Route::get('report/apps/{app}', [ReportController::class, 'index']);
