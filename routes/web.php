<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberPtPackageController;
use App\Http\Controllers\PtPackageController;
use App\Http\Controllers\PtSessionController;
use App\Http\Controllers\PtSessionPlanController;
use App\Http\Controllers\RfidCardController;
use App\Http\Controllers\SettingsController;
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
    Route::get('/dashboard/analytics', [DashboardController::class, 'index'])->name('dashboard.analytics');
    Route::get('/dashboard/sales', [DashboardController::class, 'sales'])->name('dashboard.sales');
    Route::get('/dashboard/members', [DashboardController::class, 'members'])->name('dashboard.members');
    Route::get('/dashboard/coaches', [DashboardController::class, 'coaches'])->name('dashboard.coaches');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('members', MemberController::class);
    Route::get('members/{member}/assign-keyfob', [MemberController::class, 'assignKeyfob'])->name('members.assign-keyfob');
    Route::post('members/{member}/store-keyfob', [MemberController::class, 'storeKeyfob'])->name('members.store-keyfob');
    Route::get('members/{member}/subscribe-pt-package', [MemberPtPackageController::class, 'subscribe'])->name('members.subscribe-pt-package');
    Route::post('members/{member}/subscribe-pt-package', [MemberPtPackageController::class, 'storeSubscribe'])->name('members.store-subscribe-pt-package');
    Route::get('members/{member}/log-pt-session', [PtSessionController::class, 'create'])->name('members.log-pt-session');
    Route::post('members/{member}/pt-sessions', [PtSessionController::class, 'store'])->name('members.pt-sessions.store');

    Route::resource('rfid-cards', RfidCardController::class);
    Route::get('access-logs/recent', [AccessLogController::class, 'recent'])->name('access-logs.recent');
    Route::resource('access-logs', AccessLogController::class)->only(['index']);
    Route::resource('wifi-configurations', WifiConfigurationController::class);

    Route::resource('subscriptions', SubscriptionController::class);
    Route::get('members/{member}/add-subscription', [SubscriptionController::class, 'createForMember'])->name('subscriptions.create-for-member');
    Route::post('members/{member}/store-subscription', [SubscriptionController::class, 'storeForMember'])->name('subscriptions.store-for-member');
    Route::get('member-subscriptions/{memberSubscription}/freeze', [SubscriptionController::class, 'freeze'])->name('member-subscriptions.freeze');
    Route::post('member-subscriptions/{memberSubscription}/freeze', [SubscriptionController::class, 'storeFreeze'])->name('member-subscriptions.freeze.store');
    Route::post('member-subscriptions/{memberSubscription}/unfreeze', [SubscriptionController::class, 'unfreeze'])->name('member-subscriptions.unfreeze');

    Route::resource('coaches', CoachController::class);

    Route::resource('pt-session-plans', PtSessionPlanController::class);
    Route::resource('pt-packages', PtPackageController::class);

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
