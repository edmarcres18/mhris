<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Monitor notification performance
        DB::listen(function($query) {
            if (str_contains($query->sql, 'notifications')) {
                Log::debug('Notification Query', [
                    'time' => $query->time,
                    'sql' => $query->sql
                ]);
            }
        });

        // Force HTTPS in production, HTTP in other environments
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        } else {
            URL::forceScheme('http');
        }
    }
}
