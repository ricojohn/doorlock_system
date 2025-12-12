<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RfidCardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WifiConfigurationController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('members', MemberController::class);
    Route::post('members/{member}/renew', [SubscriptionController::class, 'renew'])->name('members.renew');
    Route::get('members/{member}/assign-keyfob', [MemberController::class, 'assignKeyfob'])->name('members.assign-keyfob');
    Route::post('members/{member}/store-keyfob', [MemberController::class, 'storeKeyfob'])->name('members.store-keyfob');

    Route::resource('coaches', CoachController::class);

    Route::resource('subscriptions', SubscriptionController::class);

    Route::resource('plans', PlanController::class);

    Route::resource('rfid-cards', RfidCardController::class);
    Route::get('access-logs/recent', [AccessLogController::class, 'recent'])->name('access-logs.recent');
    Route::resource('access-logs', AccessLogController::class)->only(['index']);
    Route::resource('wifi-configurations', WifiConfigurationController::class);
});
