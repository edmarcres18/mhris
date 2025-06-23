<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;

class ResetLeaveBalances extends Command
{
    protected $signature = 'leaves:reset';
    protected $description = 'Reset leave balances for all employees';

    public function handle()
    {
        Employee::query()->update([
            'sick_leave' => 7 * 24,
            'emergency_leave' => 3 * 24,
            'vacation_leave' => 5 * 24,
        ]);

        $this->info('Leave balances have been reset for all employees.');
    }
}
