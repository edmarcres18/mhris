<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HolidayController;

class FetchHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch holidays from Google Calendar and store them in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $holidayController = new HolidayController();
        $result = $holidayController->fetchHolidaysFromGoogleCalendar();

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        return 0;
    }
}
