<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\StoreAttendance::class,
        \App\Console\Commands\CreateDatabaseBackup::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('attendance:store');
        $schedule->command('holidays:fetch')->yearly()->at('00:00');
        $schedule->command('leaves:reset')->yearly()->at('00:00');
        $schedule->command('database:backup')->daily()->at('04:00');

        $schedule->call(function () {
            $controller = new \App\Http\Controllers\NotificationsController();
            $controller->generateNotifications();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
