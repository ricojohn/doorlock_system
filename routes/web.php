<?php

use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\User;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MemberPtPackageController;
use App\Http\Controllers\PtPackageController;
use App\Http\Controllers\PtSessionController;
use App\Http\Controllers\PtSessionPlanController;
use App\Http\Controllers\RfidCardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WifiConfigurationController;
use Illuminate\Support\Facades\Route;

// Bind 'staff' route parameter to User model
Route::bind('staff', fn (string $value) => User::findOrFail($value));

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
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('permission:view_dashboard')->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::get('/dashboard/analytics', [DashboardController::class, 'index'])->name('dashboard.analytics');
        Route::get('/dashboard/sales', [DashboardController::class, 'sales'])->name('dashboard.sales');
        Route::get('/dashboard/members', [DashboardController::class, 'members'])->name('dashboard.members');
        Route::get('/dashboard/coaches', [DashboardController::class, 'coaches'])->name('dashboard.coaches');
    });

    Route::middleware('permission:manage_members')->group(function (): void {
        Route::resource('members', MemberController::class);
        Route::get('members/{member}/assign-keyfob', [MemberController::class, 'assignKeyfob'])->name('members.assign-keyfob');
        Route::post('members/{member}/store-keyfob', [MemberController::class, 'storeKeyfob'])->name('members.store-keyfob');
        Route::get('members/{member}/subscribe-pt-package', [MemberPtPackageController::class, 'subscribe'])->name('members.subscribe-pt-package');
        Route::post('members/{member}/subscribe-pt-package', [MemberPtPackageController::class, 'storeSubscribe'])->name('members.store-subscribe-pt-package');
        Route::get('members/{member}/log-pt-session', [PtSessionController::class, 'create'])->name('members.log-pt-session');
        Route::post('members/{member}/pt-sessions', [PtSessionController::class, 'store'])->name('members.pt-sessions.store');
        Route::resource('guests', GuestController::class);
        Route::get('guests/{guest}/convert-to-member', [GuestController::class, 'convertToMemberForm'])->name('guests.convert-to-member.form');
        Route::post('guests/{guest}/convert-to-member', [GuestController::class, 'convertToMember'])->name('guests.convert-to-member');
    });

    Route::middleware('permission:manage_subscriptions')->group(function (): void {
        Route::get('members/{member}/add-subscription', [SubscriptionController::class, 'createForMember'])->name('subscriptions.create-for-member');
        Route::post('members/{member}/store-subscription', [SubscriptionController::class, 'storeForMember'])->name('subscriptions.store-for-member');
        Route::get('member-subscriptions/{memberSubscription}/freeze', [SubscriptionController::class, 'freeze'])->name('member-subscriptions.freeze');
        Route::post('member-subscriptions/{memberSubscription}/freeze', [SubscriptionController::class, 'storeFreeze'])->name('member-subscriptions.freeze.store');
        Route::post('member-subscriptions/{memberSubscription}/unfreeze', [SubscriptionController::class, 'unfreeze'])->name('member-subscriptions.unfreeze');
        Route::resource('subscriptions', SubscriptionController::class);
    });

    Route::middleware('permission:manage_coaches')->group(function (): void {
        Route::resource('staff', StaffController::class);
        Route::get('coaches', fn () => redirect()->route('staff.index'))->name('coaches.index');
        Route::get('coaches/create', fn () => redirect()->route('staff.create'))->name('coaches.create');
        Route::get('coaches/{coach}', [CoachController::class, 'show'])->name('coaches.show');
    });

    Route::middleware('permission:manage_pt_packages')->group(function (): void {
        Route::resource('pt-packages', PtPackageController::class);
    });

    Route::middleware('permission:manage_pt_session_plans')->group(function (): void {
        Route::resource('pt-session-plans', PtSessionPlanController::class);
    });

    Route::middleware('permission:manage_rfid_cards')->group(function (): void {
        Route::resource('rfid-cards', RfidCardController::class);
    });

    Route::middleware('permission:view_access_logs')->group(function (): void {
        Route::get('access-logs/recent', [AccessLogController::class, 'recent'])->name('access-logs.recent');
        Route::resource('access-logs', AccessLogController::class)->only(['index']);
    });

    Route::middleware('permission:manage_wifi_configurations')->group(function (): void {
        Route::resource('wifi-configurations', WifiConfigurationController::class);
    });

    Route::middleware('permission:manage_settings')->group(function (): void {
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:manage_roles')->group(function (): void {
        Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::get('roles-permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles-permissions.edit');
        Route::put('roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
    });
});
