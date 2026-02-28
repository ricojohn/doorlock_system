<?php

use App\Http\Controllers\Api\RfidValidationController;
use Illuminate\Support\Facades\Route;

// API Routes for ESP32 RFID System
Route::middleware('api.token')->group(function (): void {
    // Health check endpoint
    Route::get('/health', [RfidValidationController::class, 'health'])->name('api.health');

    // RFID validation endpoint
    Route::post('/validate', [RfidValidationController::class, 'validate'])->name('api.validate');

    // WiFi configuration endpoint
    Route::get('/wifi-config', [RfidValidationController::class, 'getWifiConfig'])->name('api.wifi-config');

    // Door command (ESP32 polls this; when open requested, returns open then clears)
    Route::get('/door/command', [RfidValidationController::class, 'getDoorCommand'])->name('api.door.command');
});
