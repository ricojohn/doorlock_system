<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MemberController;
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
    Route::get('/', function () {
        return view('pages.index');
    })->name('home');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('members', MemberController::class);
    Route::post('members/{member}/renew', [\App\Http\Controllers\SubscriptionController::class, 'renew'])->name('members.renew');
    Route::get('members/{member}/assign-keyfob', [\App\Http\Controllers\MemberController::class, 'assignKeyfob'])->name('members.assign-keyfob');
    Route::post('members/{member}/store-keyfob', [\App\Http\Controllers\MemberController::class, 'storeKeyfob'])->name('members.store-keyfob');
    Route::resource('subscriptions', \App\Http\Controllers\SubscriptionController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
    Route::resource('rfid-cards', \App\Http\Controllers\RfidCardController::class);
});
