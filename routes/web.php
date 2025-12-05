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
    Route::resource('subscriptions', \App\Http\Controllers\SubscriptionController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
});
