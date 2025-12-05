<?php

use App\Http\Controllers\Api\RfidValidationController;
use Illuminate\Support\Facades\Route;

// API Routes for ESP32 RFID System
Route::middleware('api.token')->group(function (): void {
    // Health check endpoint
    Route::get('/health', [RfidValidationController::class, 'health'])->name('api.health');

    // RFID validation endpoint
    Route::post('/validate', [RfidValidationController::class, 'validate'])->name('api.validate');
});
