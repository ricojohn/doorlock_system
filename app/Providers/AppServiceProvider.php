<?php

namespace App\Providers;

use App\Models\AccessLog;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layout.app', 'layout.header'], function ($view): void {
            $view->with('appSettings', Setting::getCached());
        });

        View::composer('layout.header', function ($view): void {
            $recentAccessLogsForNav = AccessLog::with('member')
                ->orderByDesc('accessed_at')
                ->limit(4)
                ->get();
            $view->with('recentAccessLogsForNav', $recentAccessLogsForNav);
        });
    }
}
