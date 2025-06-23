<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register routes with both web and api middleware to ensure authentication works
        Broadcast::routes(['middleware' => ['web', 'auth']]);
        Broadcast::routes(['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']]);

        require base_path('routes/channels.php');
    }
}
