<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AddReasonColumnsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-reason-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add reason column to overtime_pays and night_premia tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to add reason columns...');

        try {
            // Add reason column to overtime_pays table
            if (!Schema::hasColumn('overtime_pays', 'reason')) {
                Schema::table('overtime_pays', function (Blueprint $table) {
                    $table->text('reason')->nullable()->after('approval_status');
                });
                $this->info('Successfully added reason column to overtime_pays table');
            } else {
                $this->warn('Reason column already exists in overtime_pays table');
            }

            // Add reason column to night_premia table
            if (!Schema::hasColumn('night_premia', 'reason')) {
                Schema::table('night_premia', function (Blueprint $table) {
                    $table->text('reason')->nullable()->after('approval_status');
                });
                $this->info('Successfully added reason column to night_premia table');
            } else {
                $this->warn('Reason column already exists in night_premia table');
            }

            $this->info('All columns added successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error adding columns: ' . $e->getMessage());
            return 1;
        }
    }
}
